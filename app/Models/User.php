<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** 
     * Menggunakan trait:
     * - HasFactory → untuk kebutuhan factory / seeding
     * - Notifiable → agar user bisa menerima notifikasi (email, dll)
     */
    use HasFactory, Notifiable;

    /**
     * Atribut yang boleh diisi secara mass assignment (create / update).
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',        // Nama user
        'email',       // Email user
        'student_id',  // NIM (khusus mahasiswa)
        'role',        // Role user (misal: admin / student)
        'password',    // Password
    ];

    /**
     * Atribut yang disembunyikan saat model di-serialize (misalnya ke JSON).
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',        // Tidak ditampilkan demi keamanan
        'remember_token',  // Token remember me
    ];

    /**
     * Casting atribut otomatis ke tipe data tertentu.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime', // Otomatis jadi object datetime
            'password' => 'hashed',            // Otomatis di-hash saat disimpan
        ];
    }
}