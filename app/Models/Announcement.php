<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    // Menentukan kolom yang boleh diisi secara mass assignment (create / update)
    protected $fillable = [
        'title',        // Judul pengumuman
        'category',     // Kategori pengumuman
        'posted_by',    // Nama pembuat / pengunggah
        'action_type',  // Tipe aksi (download / view)
        'file_path',    // Path file jika upload PDF
        'external_url', // Link eksternal jika bukan upload file
        'is_active',    // Status aktif / tidak
    ];

    // Casting tipe data otomatis
    protected $casts = [
        'is_active' => 'boolean', // Pastikan is_active selalu boolean (true/false)
    ];

    // Daftar kategori yang tersedia (konstanta)
    // Biasanya digunakan untuk dropdown di form
    public const CATEGORIES = [
        'panduan'   => 'Panduan',
        'jadwal'    => 'Jadwal',
        'informasi' => 'Informasi',
        'lainnya'   => 'Lainnya',
    ];
}