<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use App\Notifications\ProposalRejectedNotification;
use App\Notifications\ProposalVerifiedNotification;
use Illuminate\Http\Request;

class AdminProposalController extends Controller
{
    // Menampilkan daftar proposal untuk admin
    public function index()
    {
        $proposals = Proposal::with(['topic', 'selectedDosen', 'student'])
            ->orderByRaw("
                CASE status
                    WHEN 'pending' THEN 1
                    WHEN 'rejected' THEN 2
                    WHEN 'verified' THEN 3
                    ELSE 4
                END
            ")
            ->latest('id')
            ->paginate(10);

        return view('admin.proposals.index', compact('proposals'));
    }

    // Menampilkan detail satu proposal
    public function show(Proposal $proposal)
    {
        $proposal->load(['topic', 'selectedDosen', 'student']);

        return view('admin.proposals.show', compact('proposal'));
    }

    // Mengubah status proposal (pending / rejected / verified)
    public function updateStatus(Request $request, Proposal $proposal)
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,rejected,verified'],
            'rejection_reason' => ['nullable', 'string', 'max:2000'],
        ]);

        $oldStatus = $proposal->status;
        $newStatus = $data['status'];

        $rejectionReason = ($newStatus === 'rejected')
            ? ($data['rejection_reason'] ?? null)
            : null;

        $proposal->update([
            'status' => $newStatus,
            'rejection_reason' => $rejectionReason,
        ]);

        if ($proposal->student && $proposal->student->email) {
            if ($oldStatus !== 'verified' && $newStatus === 'verified') {
                $proposal->student->notify(new ProposalVerifiedNotification($proposal));
            }

            if ($oldStatus !== 'rejected' && $newStatus === 'rejected') {
                $proposal->student->notify(new ProposalRejectedNotification($proposal));
            }
        }

        return back()->with('success', 'Status pengajuan mahasiswa berhasil diperbarui.');
    }
}