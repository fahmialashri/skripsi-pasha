<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use Illuminate\Http\Request;

class StudentProfileController extends Controller
{
    // Menampilkan halaman profil mahasiswa
    public function index(Request $request)
    {
        // Ambil user yang sedang login
        $user = $request->user();

        // Ambil proposal terakhir mahasiswa
        $latestProposal = Proposal::where('student_user_id', $user->id)
            ->latest('id')
            ->first();

        // No HP diambil dari field whatsapp pada proposal terakhir
        // Jika tidak ada, tampilkan "-"
        $phone = $latestProposal->whatsapp ?? '-';

        // Ambil 2 digit pertama student_id untuk menentukan angkatan (format 20XX)
        if ($user->student_id && strlen($user->student_id) >= 2) {
            $tahun2Digit = substr($user->student_id, 0, 2);
            $angkatan = '20' . $tahun2Digit;
        } else {
            $angkatan = '-';
        }

        // Ambil seluruh riwayat proposal mahasiswa
        $proposals = Proposal::with(['selectedDosen', 'topic'])
            ->where('student_user_id', $user->id)
            ->latest('id')
            ->get();

        // Program studi (hardcoded)
        $prodi = 'Teknik Informatika';

        // Kirim data ke view student.profile
        return view('student.profile', compact(
            'user',
            'phone',
            'angkatan',
            'prodi',
            'proposals'
        ));
    }

    // Memperbarui data profil mahasiswa (nama)
    public function update(Request $request)
    {
        // Ambil user yang sedang login
        $user = $request->user();

        // Validasi input nama
        $data = $request->validate([
            'name' => ['required', 'string', 'max:200'],
        ]);

        // Update nama user
        $user->update([
            'name' => $data['name'],
        ]);

        // Kembali dengan pesan sukses
        return back()->with('profile_updated', 'Nama berhasil diperbarui.');
    }
}