<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use Illuminate\Http\Request; // Tambahkan ini

class AdminDashboardController extends Controller
{
    public function index(Request $request) // Tambahkan Request $request
    {
        // 1. Menghitung jumlah total (ini tetap, agar angka di card tidak hilang)
        $counts = [
            'pending'  => Proposal::where('status', 'pending')->count(),
            'rejected' => Proposal::where('status', 'rejected')->count(),
            'verified' => Proposal::where('status', 'verified')->count(),
        ];

        // 2. Mulai Query untuk Tabel
        $query = Proposal::query();

        // 3. LOGIKA FILTER: Cek apakah ada ?status= di URL
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // 4. LOGIKA SEARCH: Cek apakah ada ?search= di URL
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('student_name', 'like', '%' . $request->search . '%')
                  ->orWhere('student_id', 'like', '%' . $request->search . '%');
            });
        }

        // 5. Ambil datanya (latest)
        $latest = $query->latest('id')->get();

        return view('admin.dashboard', compact('counts', 'latest'));
    }
}