<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    // Kolom yang boleh diisi secara mass assignment
    protected $fillable = [
        'name',       // Nama dosen
        'title',      // Gelar dosen (misalnya: S.Kom., M.Kom.)
        'expertise',  // Bidang keahlian dosen
        'quota'       // Kuota bimbingan mahasiswa
    ];

    // Relasi: satu dosen bisa memiliki banyak proposal
    public function proposals()
    {
        // selected_dosen_id adalah foreign key di tabel proposals
        return $this->hasMany(Proposal::class, 'selected_dosen_id');
    }
}