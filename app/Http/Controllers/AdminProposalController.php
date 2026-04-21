<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Notifications\ProposalRejectedNotification;
use App\Notifications\ProposalVerifiedNotification;

class AdminProposalController extends Controller
{
    // Menampilkan daftar proposal untuk admin
    public function index()
    {
        $proposals = Proposal::with([
                'topic',
                'selectedDosen',
                'kaprodiRecommendedDosen',
                'student'
            ])
            ->orderByRaw("
                CASE
                    WHEN status = 'pending' THEN 1
                    WHEN status = 'rejected' THEN 2
                    WHEN status = 'verified' THEN 3
                    ELSE 4
                END
            ")
            ->latest('id')
            ->paginate(10);

        return view('admin.proposals.index', compact('proposals'));
    }

    // Menampilkan detail satu proposal
    public function show(Proposal $proposal)
    {
        $proposal->load([
            'topic',
            'student',
            'selectedDosen' => function ($query) {
                $query->withCount([
                    'proposals as assigned_count' => function ($q) {
                        $q->where('status', 'verified');
                    }
                ]);
            },
            'kaprodiRecommendedDosen',
        ]);

        $topicName = optional($proposal->topic)->name;

        // Rekomendasi dosen berdasarkan expertise yang mirip dengan topik
        $recommendedDosens = Dosen::query()
            ->when($topicName, function ($query) use ($topicName) {
                $query->where('expertise', 'ilike', '%' . $topicName . '%');
            })
            ->withCount([
                'proposals as assigned_count' => function ($query) {
                    $query->where('status', 'verified');
                }
            ])
            ->orderBy('name')
            ->get();

        // Semua dosen untuk fallback manual
        $allDosens = Dosen::query()
            ->withCount([
                'proposals as assigned_count' => function ($query) {
                    $query->where('status', 'verified');
                }
            ])
            ->orderBy('name')
            ->get();

        // Generate signed URL KRS dari Supabase Storage
        $krsPreviewUrl = null;
        $krsMimeType = null;
        $isImage = false;
        $isPdf = false;

        if (!empty($proposal->krs_file)) {
            try {
                $bucket = config('services.supabase.bucket', 'krs');
                $supabaseUrl = rtrim((string) config('services.supabase.url'), '/');
                $supabaseKey = config('services.supabase.key');

                if (!$supabaseUrl || !$supabaseKey || !$bucket) {
                    Log::error('Konfigurasi Supabase belum lengkap untuk preview KRS', [
                        'url' => $supabaseUrl,
                        'bucket' => $bucket,
                        'has_key' => !empty($supabaseKey),
                    ]);
                } else {
                    $response = Http::withHeaders([
                        'apikey' => $supabaseKey,
                        'Authorization' => 'Bearer ' . $supabaseKey,
                        'Content-Type' => 'application/json',
                    ])->post("{$supabaseUrl}/storage/v1/object/sign/{$bucket}/{$proposal->krs_file}", [
                        'expiresIn' => 3600,
                    ]);

                    if ($response->successful()) {
                        $signedPath = $response->json('signedURL');

                        if ($signedPath) {
                            $krsPreviewUrl = $supabaseUrl . '/storage/v1' . $signedPath;
                        }
                    } else {
                        Log::error('Gagal membuat signed URL KRS', [
                            'status' => $response->status(),
                            'body' => $response->body(),
                            'path' => $proposal->krs_file,
                        ]);
                    }
                }

                $extension = strtolower(pathinfo($proposal->krs_file, PATHINFO_EXTENSION));
                $imageExtensions = ['jpg', 'jpeg', 'png', 'webp'];
                $pdfExtensions = ['pdf'];

                $isImage = in_array($extension, $imageExtensions, true);
                $isPdf = in_array($extension, $pdfExtensions, true);

                if ($isImage) {
                    $krsMimeType = 'image';
                } elseif ($isPdf) {
                    $krsMimeType = 'pdf';
                } else {
                    $krsMimeType = 'other';
                }
            } catch (\Throwable $e) {
                Log::error('Error saat generate signed URL KRS', [
                    'message' => $e->getMessage(),
                    'path' => $proposal->krs_file,
                ]);
            }
        }

        return view('admin.proposals.show', compact(
            'proposal',
            'recommendedDosens',
            'allDosens',
            'krsPreviewUrl',
            'krsMimeType',
            'isImage',
            'isPdf'
        ));
    }

    // Update status proposal
    public function updateStatus(Request $request, Proposal $proposal)
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,rejected,verified'],
            'rejection_reason' => ['nullable', 'string', 'max:2000'],
            'recommended_dosen_id' => ['nullable', 'integer', 'exists:dosens,id'],
            'manual_dosen_id' => ['nullable', 'integer', 'exists:dosens,id'],
        ]);

        $proposal->load([
            'student',
            'selectedDosen',
            'kaprodiRecommendedDosen',
            'topic'
        ]);

        $oldStatus = $proposal->status;
        $newStatus = $data['status'];

        $kaprodiRecommendationId = null;

        if (!empty($data['manual_dosen_id'])) {
            $kaprodiRecommendationId = (int) $data['manual_dosen_id'];
        } elseif (!empty($data['recommended_dosen_id'])) {
            $kaprodiRecommendationId = (int) $data['recommended_dosen_id'];
        }

        if ($newStatus === 'verified' && !$kaprodiRecommendationId && !$proposal->selected_dosen_id) {
            return back()->withErrors([
                'status' => 'Dosen pembimbing final harus dipilih sebelum proposal diverifikasi.'
            ])->withInput();
        }

        $rejectionReason = $newStatus === 'rejected'
            ? ($data['rejection_reason'] ?? null)
            : null;

        $updatePayload = [
            'status' => $newStatus,
            'rejection_reason' => $rejectionReason,
            'kaprodi_recommended_dosen_id' => null,
            'kaprodi_recommendation_note' => null,
        ];

        if ($kaprodiRecommendationId) {
            $updatePayload['kaprodi_recommended_dosen_id'] = $kaprodiRecommendationId;
            $updatePayload['kaprodi_recommendation_note'] = 'Rekomendasi dosen pengganti dari kaprodi.';
        }

        if ($newStatus === 'verified') {
            $finalDosenId = $kaprodiRecommendationId ?: $proposal->selected_dosen_id;
            $updatePayload['selected_dosen_id'] = $finalDosenId;
            $updatePayload['rejection_reason'] = null;
        }

        if ($newStatus === 'pending') {
            $updatePayload['rejection_reason'] = null;
        }

        $proposal->update($updatePayload);

        $proposal->load([
            'student',
            'selectedDosen',
            'kaprodiRecommendedDosen',
            'topic'
        ]);

        try {
            if ($proposal->student && $proposal->student->email) {
                if ($oldStatus !== 'verified' && $newStatus === 'verified') {
                    $proposal->student->notify(new ProposalVerifiedNotification($proposal));
                }

                if ($oldStatus !== 'rejected' && $newStatus === 'rejected') {
                    $proposal->student->notify(new ProposalRejectedNotification($proposal));
                }
            }
        } catch (\Throwable $e) {
            Log::error('Gagal kirim notifikasi proposal', [
                'message' => $e->getMessage(),
                'proposal_id' => $proposal->id,
            ]);
        }

        return back()->with('success', 'Status pengajuan mahasiswa berhasil diperbarui.');
    }
}