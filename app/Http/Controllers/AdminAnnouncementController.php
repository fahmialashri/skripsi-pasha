<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminAnnouncementController extends Controller
{
    // Menampilkan daftar pengumuman dengan fitur search & filter
    public function index(Request $request)
    {
        // Ambil keyword pencarian dan kategori dari request
        $q = trim((string) $request->get('q'));
        $category = $request->get('category');

        // Query data announcement dengan kondisi opsional
        $announcements = Announcement::query()
            // Jika ada keyword, filter berdasarkan title
            ->when($q, fn($qq) => $qq->where('title', 'like', "%{$q}%"))
            // Jika ada kategori, filter berdasarkan kategori
            ->when($category, fn($qq) => $qq->where('category', $category))
            // Urutkan berdasarkan id terbaru
            ->latest('id')
            // Pagination 10 data per halaman
            ->paginate(10)
            // Supaya query string tetap ada saat pindah halaman
            ->withQueryString();

        // Ambil daftar kategori dari konstanta model
        $categories = Announcement::CATEGORIES;

        // Kirim data ke view index
        return view('admin.announcements.index', compact('announcements', 'categories', 'q', 'category'));
    }

    // Menampilkan halaman form tambah pengumuman
    public function create()
    {
        $categories = Announcement::CATEGORIES;
        return view('admin.announcements.create', compact('categories'));
    }

    // Menyimpan data pengumuman baru
    public function store(Request $request)
    {
        // Validasi input
        $data = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'category'     => ['required', 'string', 'max:50'],
            'posted_by'    => ['nullable', 'string', 'max:150'],
            'action_type'  => ['required', 'in:download,view'],
            'file'         => ['nullable', 'file', 'mimes:pdf', 'max:5120'], // max 5MB
            'external_url' => ['nullable', 'url'],
            'is_active'    => ['nullable', 'boolean'],
        ]);

        // Minimal harus ada file atau link eksternal
        if (!$request->hasFile('file') && empty($data['external_url'])) {
            return back()->withErrors(['file' => 'Upload file PDF atau isi link eksternal.'])->withInput();
        }

        $filePath = null;

        // Jika ada file yang diupload
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Buat nama file unik agar tidak bentrok
            $safeName = 'announcement_' . now()->format('Ymd_His') . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();

            // Simpan file ke storage/app/public/announcements
            $stored = $file->storeAs('public/announcements', $safeName);

            // Hilangkan kata "public/" agar sesuai dengan storage link
            $filePath = Str::replaceFirst('public/', '', $stored);
        }

        // Simpan data ke database
        Announcement::create([
            'title'        => $data['title'],
            'category'     => $data['category'],
            'posted_by'    => $data['posted_by'] ?? 'Admin', // default Admin jika kosong
            'action_type'  => $data['action_type'],
            'file_path'    => $filePath,
            'external_url' => $data['external_url'] ?? null,
            'is_active'    => $request->boolean('is_active', true), // default true
        ]);

        return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman berhasil dibuat.');
    }

    // Menampilkan halaman edit pengumuman
    public function edit(Announcement $announcement)
    {
        $categories = Announcement::CATEGORIES;
        return view('admin.announcements.edit', compact('announcement', 'categories'));
    }

    // Memperbarui data pengumuman
    public function update(Request $request, Announcement $announcement)
    {
        // Validasi input
        $data = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'category'     => ['required', 'string', 'max:50'],
            'posted_by'    => ['nullable', 'string', 'max:150'],
            'action_type'  => ['required', 'in:download,view'],
            'file'         => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
            'external_url' => ['nullable', 'url'],
            'is_active'    => ['nullable', 'boolean'],
        ]);

        // Ambil file lama (jika ada)
        $filePath = $announcement->file_path;

        // Jika upload file baru
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Buat nama file unik
            $safeName = 'announcement_' . now()->format('Ymd_His') . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();

            // Simpan file baru
            $stored = $file->storeAs('public/announcements', $safeName);

            // Simpan path baru
            $filePath = Str::replaceFirst('public/', '', $stored);
        }

        $externalUrl = $data['external_url'] ?? null;

        // Pastikan minimal ada file atau link
        if (!$filePath && !$externalUrl) {
            return back()->withErrors(['file' => 'Upload file PDF atau isi link eksternal.'])->withInput();
        }

        // Update data di database
        $announcement->update([
            'title'        => $data['title'],
            'category'     => $data['category'],
            'posted_by'    => $data['posted_by'] ?? 'Admin',
            'action_type'  => $data['action_type'],
            'file_path'    => $filePath,
            'external_url' => $externalUrl,
            'is_active'    => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    // Menghapus pengumuman
    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return back()->with('success', 'Pengumuman berhasil dihapus.');
    }
}