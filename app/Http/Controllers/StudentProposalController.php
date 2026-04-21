<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Proposal;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StudentProposalController extends Controller
{
    /**
     * Menampilkan halaman form pengajuan proposal
     */
    public function create(Request $request)
    {
        $user = Auth::user();

        $topics = Topic::orderBy('name')->get();

        $selectedDosenId = $request->query('selected_dosen_id');
        $selectedDosen = null;
        $autoTopicId = null;

        if ($selectedDosenId) {
            $selectedDosen = Dosen::find($selectedDosenId);

            if ($selectedDosen) {
                foreach ($topics as $topic) {
                    if (Str::contains(Str::lower($selectedDosen->expertise), Str::lower($topic->name))) {
                        $autoTopicId = $topic->id;
                        break;
                    }
                }
            }
        }

        return view('student.proposal.create', compact(
            'user',
            'topics',
            'selectedDosen',
            'autoTopicId'
        ));
    }

    /**
     * Endpoint AJAX untuk filter dosen berdasarkan topik
     */
    public function dosensByTopic(Request $request)
    {
        $request->validate([
            'topic_id' => ['required', 'integer', 'exists:topics,id'],
        ]);

        $topic = Topic::findOrFail($request->topic_id);
        $keyword = trim($topic->name);

        $dosens = Dosen::query()
            ->where('expertise', 'ilike', '%' . $keyword . '%')
            ->orderBy('name')
            ->get(['id', 'name', 'title']);

        return response()->json($dosens);
    }

    /**
     * Menyimpan data pengajuan ke database + upload KRS ke Supabase Storage
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'student_name'        => ['required', 'string', 'max:200'],
            'student_id'          => ['required', 'string', 'max:50'],
            'whatsapp'            => ['required', 'string', 'max:30'],
            'title'               => ['required', 'string', 'max:500'],
            'topic_id'            => ['required', 'integer', 'exists:topics,id'],
            'selected_dosen_id'   => ['required', 'integer', 'exists:dosens,id'],
            'graduation_estimate' => ['required', 'string'],
            'abstract'            => ['required', 'string'],
            'krs_file'            => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ]);

        $filePath = null;

        try {
            if ($request->hasFile('krs_file')) {
                $file = $request->file('krs_file');

                $bucket = env('SUPABASE_BUCKET', 'krs');
                $supabaseUrl = rtrim(env('SUPABASE_URL'), '/');
                $supabaseKey = env('SUPABASE_KEY');

                if (!$supabaseUrl || !$supabaseKey) {
                    return back()->withErrors([
                        'krs_file' => 'Konfigurasi Supabase belum lengkap.'
                    ])->withInput();
                }

                $extension = $file->getClientOriginalExtension();
                $safeStudentId = preg_replace('/[^A-Za-z0-9_\-]/', '_', $validated['student_id']);
                $fileName = 'krs_' . $safeStudentId . '_' . now()->format('YmdHis') . '.' . $extension;

                // Simpan dalam folder bucket, mis. 2026/04/nama_file.pdf
                $objectPath = now()->format('Y/m') . '/' . $fileName;

                $fileContent = file_get_contents($file->getRealPath());

                $response = Http::withBody($fileContent, $file->getMimeType())
                    ->withHeaders([
                        'apikey' => $supabaseKey,
                        'Authorization' => 'Bearer ' . $supabaseKey,
                        'x-upsert' => 'false',
                    ])
                    ->post("{$supabaseUrl}/storage/v1/object/{$bucket}/{$objectPath}");

                if ($response->failed()) {
                    Log::error('Upload KRS ke Supabase gagal', [
                        'status' => $response->status(),
                        'body' => $response->body(),
                        'student_id' => $validated['student_id'],
                    ]);

                    return back()->withErrors([
                        'krs_file' => 'Upload file KRS gagal. Pastikan bucket dan policy Supabase sudah benar.'
                    ])->withInput();
                }

                // Simpan path object untuk dipakai nanti saat generate signed URL
                $filePath = $objectPath;
            }

            Proposal::create([
                'student_user_id'     => $user->id,
                'student_name'        => $validated['student_name'],
                'student_id'          => $validated['student_id'],
                'whatsapp'            => $validated['whatsapp'],
                'title'               => $validated['title'],
                'abstract'            => $validated['abstract'],
                'topic_id'            => $validated['topic_id'],
                'graduation_estimate' => $validated['graduation_estimate'],
                'selected_dosen_id'   => $validated['selected_dosen_id'],
                'status'              => 'pending',
                'krs_file'            => $filePath,
            ]);

            return redirect()
                ->route('student.dashboard')
                ->with('success', 'Pengajuan dosen pembimbing berhasil dikirim! Silakan tunggu verifikasi admin.');
        } catch (\Throwable $e) {
            Log::error('Gagal menyimpan proposal mahasiswa', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'student_user_id' => $user?->id,
            ]);

            return back()->withErrors([
                'general' => 'Terjadi kesalahan saat mengirim pengajuan. Silakan coba lagi.'
            ])->withInput();
        }
    }
}