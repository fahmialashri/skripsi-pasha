<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Proposal;
use Illuminate\Http\Request;
use App\Notifications\ProposalRejectedNotification;
use App\Notifications\ProposalVerifiedNotification;
use Illuminate\Support\Facades\Log;

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
                $query->where('expertise', 'like', '%' . $topicName . '%');
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

        return view('admin.proposals.show', compact(
            'proposal',
            'recommendedDosens',
            'allDosens'
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

        // Tentukan rekomendasi dosen dari kaprodi
        $kaprodiRecommendationId = null;

        if (!empty($data['manual_dosen_id'])) {
            $kaprodiRecommendationId = (int) $data['manual_dosen_id'];
        } elseif (!empty($data['recommended_dosen_id'])) {
            $kaprodiRecommendationId = (int) $data['recommended_dosen_id'];
        }

        // Validasi kalau mau verified harus ada dosen final
        if ($newStatus === 'verified' && !$kaprodiRecommendationId && !$proposal->selected_dosen_id) {
            return back()->withErrors([
                'status' => 'Dosen pembimbing final harus dipilih sebelum proposal diverifikasi.'
            ])->withInput();
        }

        // Tentukan alasan penolakan
        $rejectionReason = $newStatus === 'rejected'
            ? ($data['rejection_reason'] ?? null)
            : null;

        // Reset default dulu (biar data lama tidak nyangkut)
        $updatePayload = [
            'status' => $newStatus,
            'rejection_reason' => $rejectionReason,
            'kaprodi_recommended_dosen_id' => null,
            'kaprodi_recommendation_note' => null,
        ];

        // Isi kalau ada rekomendasi
        if ($kaprodiRecommendationId) {
            $updatePayload['kaprodi_recommended_dosen_id'] = $kaprodiRecommendationId;
            $updatePayload['kaprodi_recommendation_note'] = 'Rekomendasi dosen pengganti dari kaprodi.';
        }

        // Jika disetujui (verified)
        if ($newStatus === 'verified') {
            $finalDosenId = $kaprodiRecommendationId ?: $proposal->selected_dosen_id;

            $updatePayload['selected_dosen_id'] = $finalDosenId;
            $updatePayload['rejection_reason'] = null;
        }

        // Jika kembali ke pending
        if ($newStatus === 'pending') {
            $updatePayload['rejection_reason'] = null;
        }

        $proposal->update($updatePayload);

        // Reload relasi
        $proposal->load([
            'student',
            'selectedDosen',
            'kaprodiRecommendedDosen',
            'topic'
        ]);

        // Kirim notifikasi (aman untuk production)
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
            Log::error('Gagal kirim notifikasi proposal: ' . $e->getMessage());
        }

        return back()->with('success', 'Status pengajuan mahasiswa berhasil diperbarui.');
    }
}