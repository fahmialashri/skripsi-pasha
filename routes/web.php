<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\StudentProposalController;
use App\Http\Controllers\AdminProposalController;
use App\Http\Controllers\StudentDosenController;
use App\Http\Controllers\AdminDosenController;
use App\Http\Controllers\StudentProfileController;

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        $user = Auth::user();

        return $user && $user->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('student.dashboard');
    })->name('dashboard');

    // STUDENT
    Route::middleware('role:mahasiswa')->group(function () {
        Route::get('/mahasiswa/dashboard', [StudentDashboardController::class, 'index'])
            ->name('student.dashboard');

        //FORM PENGAJUAN
        Route::get('/mahasiswa/pengajuan', [StudentProposalController::class, 'create'])
            ->name('student.proposal.create');

        Route::post('/mahasiswa/pengajuan', [StudentProposalController::class, 'store'])
            ->name('student.proposal.store');

        //dosen berdasarkan topik
        Route::get('/mahasiswa/dosens-by-topic', [StudentProposalController::class, 'dosensByTopic'])
            ->name('student.dosens.byTopic');
        
        //dosen berdasarkan pencarian
        Route::get('/mahasiswa/dosen', [StudentDosenController::class, 'index'])
            ->name('student.dosen.index');
          
            // REALTIME KUOTA (polling)
        Route::get('/mahasiswa/dosen-availability', [StudentDosenController::class, 'availability'])
            ->name('student.dosen.availability');
            
            // PROFILE MAHASISWA
        Route::get('/mahasiswa/profil', [StudentProfileController::class, 'index'])
        ->name('student.profile');
        Route::put('/mahasiswa/profil', [StudentProfileController::class, 'update'])
        ->name('student.profile.update');
    });

    // ADMIN
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
            ->name('admin.dashboard');

        // LIST PENGAJUAN MASUK (yang pending/semua)
        Route::get('/admin/proposals', [AdminProposalController::class, 'index'])
            ->name('admin.proposals.index');
        Route::get('/admin/proposals/{proposal}', [AdminProposalController::class, 'show'])
            ->name('admin.proposals.show');
        Route::post('/admin/proposals/{proposal}/status', [AdminProposalController::class, 'updateStatus'])
            ->name('admin.proposals.status');
         
        // CRUD DOSEN
        Route::get('/admin/dosens', [AdminDosenController::class, 'index'])
            ->name('admin.dosens.index');
        Route::post('/admin/dosens', [AdminDosenController::class, 'store'])
            ->name('admin.dosens.store');
        Route::put('/admin/dosens/{dosen}', [AdminDosenController::class, 'update'])
            ->name('admin.dosens.update');
        Route::delete('/admin/dosens/{dosen}', [AdminDosenController::class, 'destroy'])
            ->name('admin.dosens.destroy');
        
        // CRUD PENGUMUMAN
        Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
        Route::resource('announcements', \App\Http\Controllers\AdminAnnouncementController::class)->except(['show']);
});

    });

});

require __DIR__.'/auth.php';