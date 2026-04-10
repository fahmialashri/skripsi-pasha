<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use App\Models\Dosen;
use App\Models\Announcement;

class StudentDashboardController extends Controller
{
    // Menampilkan dashboard mahasiswa
    public function index()
    {
        // Ambil user yang sedang login
        $user = request()->user();

        // Ambil proposal terakhir milik mahasiswa beserta relasinya
        $myProposal = Proposal::with(['topic', 'selectedDosen'])
            ->where('student_user_id', $user->id)
            ->latest('id')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Pengumuman (Hanya 2 Terbaru & Aktif)
        |--------------------------------------------------------------------------
        */
        $announcements = Announcement::where('is_active', 1)
            ->latest('id')
            ->take(2) // hanya ambil 2 pengumuman terbaru
            ->get();

        // Kirim semua data ke view student.dashboard
        return view('student.dashboard', compact(
            'user',
            'myProposal',
            'announcements'
        ));
    }
}