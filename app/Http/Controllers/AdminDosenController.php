<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Http\Request;

class AdminDosenController extends Controller
{
    // Menampilkan daftar dosen + fitur pencarian
    public function index(Request $request)
    {
        // Ambil query pencarian dari URL (?q=...)
        $q = $request->query('q');

        $dosens = Dosen::query()
            // Hitung jumlah proposal terverifikasi yang di-assign ke dosen
            ->withCount([
                'proposals as assigned_count' => fn($x) =>
                    $x->where('status', 'verified'),
            ])
            // Jika ada keyword pencarian
            ->when($q, function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    // Cari berdasarkan nama, title, atau expertise
                    $qq->where('name', 'like', "%{$q}%")
                       ->orWhere('title', 'like', "%{$q}%")
                       ->orWhere('expertise', 'like', "%{$q}%");
                });
            })
            // Urutkan berdasarkan nama (A-Z)
            ->orderBy('name')
            // Pagination 10 data per halaman
            ->paginate(10)
            // Supaya query string tetap ada saat pindah halaman
            ->withQueryString();

        // Kirim data ke view
        return view('admin.dosens.index', compact('dosens'));
    }

    // Menyimpan data dosen baru
    public function store(Request $request)
    {
        // Validasi input
        $data = $request->validate([
            'name' => ['required','string','max:200'],
            'title' => ['nullable','string','max:50'],
            'expertise' => ['nullable','string','max:255'],
            'quota' => ['nullable','integer','min:0','max:999'],
        ]);

        // Rapikan spasi berlebih pada expertise
        if (isset($data['expertise'])) {
            $data['expertise'] = trim(preg_replace('/\s+/', ' ', $data['expertise']));
        }

        // Jika quota tidak diisi, default 0
        $data['quota'] = $data['quota'] ?? 0;

        // Simpan ke database
        Dosen::create($data);

        return back()->with('success', 'Dosen berhasil ditambahkan.');
    }

    // Memperbarui data dosen
    public function update(Request $request, Dosen $dosen)
    {
        // Validasi input (quota & expertise wajib saat update)
        $data = $request->validate([
            'quota' => ['required', 'integer', 'min:0', 'max:999'],
            'expertise' => ['required', 'string', 'max:255'],
        ]);

        // Rapikan spasi berlebih pada expertise
        $data['expertise'] = trim(preg_replace('/\s+/', ' ', $data['expertise']));

        // Update data dosen
        $dosen->update($data);

        return back()->with('success', 'Data dosen berhasil diperbarui.');
    }

    // Menghapus data dosen
    public function destroy(Dosen $dosen)
    {
        // Hapus data dari database
        $dosen->delete();

        return back()->with('success', 'Dosen berhasil dihapus.');
    }
}