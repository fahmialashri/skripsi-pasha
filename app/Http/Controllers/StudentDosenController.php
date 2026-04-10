<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Topic;
use Illuminate\Http\Request;

class StudentDosenController extends Controller
{
    // Menampilkan daftar dosen untuk mahasiswa
    public function index(Request $request)
    {
        // Ambil parameter pencarian dan filter topik dari query string
        $q = $request->query('q');
        $topicId = $request->query('topic_id');

        $dosens = Dosen::query()
            ->withCount([
                // Hitung jumlah proposal dengan status 'verified' untuk setiap dosen
                // Disimpan sebagai assigned_count
                'proposals as assigned_count' => fn($x) => $x->where('status', 'verified'),
            ])
            // Jika ada keyword pencarian
            ->when($q, function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    // Cari berdasarkan nama, gelar, atau expertise
                    $qq->where('name', 'like', "%{$q}%")
                       ->orWhere('title', 'like', "%{$q}%")
                       ->orWhere('expertise', 'like', "%{$q}%");
                });
            })
            // Jika ada filter berdasarkan topic_id
            ->when($topicId, function ($query) use ($topicId) {
                // Ambil nama topik berdasarkan id
                $topicName = Topic::find($topicId)?->name;

                // Jika topik ditemukan, filter dosen berdasarkan expertise
                if ($topicName) {
                    $query->where('expertise', 'like', "%{$topicName}%");
                }
            })
            // Urutkan berdasarkan nama (A-Z)
            ->orderBy('name')
            // Pagination 12 data per halaman
            ->paginate(12)
            // Supaya query string tetap ada saat pindah halaman
            ->withQueryString();

        // Ambil semua topik untuk dropdown filter
        $topics = Topic::orderBy('name')->get();

        // Kirim data ke view
        return view('student.dosen.index', compact('dosens', 'topics'));
    }

    // Endpoint untuk mengecek ketersediaan kuota dosen (AJAX)
    public function availability(Request $request)
    {
        // Ambil daftar id dosen dari request
        $ids = $request->input('ids', []);
        $ids = is_array($ids) ? $ids : [];

        // Jika tidak ada id, kembalikan array kosong
        if (!count($ids)) return response()->json([]);

        // Ambil data dosen berdasarkan id + hitung proposal verified
        $rows = Dosen::query()
            ->whereIn('id', $ids)
            ->withCount([
                // Hitung jumlah proposal dengan status verified
                'proposals as assigned_count' => fn($q) => $q->where('status', 'verified'),
            ])
            ->get(['id', 'quota']); // Ambil hanya kolom id dan quota

        // Kembalikan data dalam bentuk JSON
        return response()->json(
            $rows->map(function ($d) {

                // Pastikan nilai integer
                $quota = (int) ($d->quota ?? 0);
                $used  = (int) ($d->assigned_count ?? 0);

                // Hitung sisa kuota
                $left  = max(0, $quota - $used);

                // Cek apakah kuota sudah penuh
                $isFull = $quota > 0 && $used >= $quota;

                return [
                    'id' => $d->id,       // ID dosen
                    'quota' => $quota,    // Total kuota
                    'used' => $used,      // Jumlah terpakai (verified)
                    'left' => $left,      // Sisa kuota
                    'isFull' => $isFull,  // Status penuh atau tidak
                ];
            })->values()
        );
    }
}