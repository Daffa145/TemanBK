<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AiCounselingController;
use App\Http\Controllers\CounselorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Halaman Utama / Landing Page
Route::get('/', function () {
    if (Auth::check()) {
        /** @var User $user */
        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return $user->isGuruBk()
            ? redirect()->route('counselor.dashboard')
            : redirect()->route('student.dashboard');
    }

    return view('welcome');
});

// Central Dashboard Redirector
Route::get('/dashboard', function () {
    /** @var User $user */
    $user = Auth::user();

    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }

    return $user->isGuruBk()
        ? redirect()->route('counselor.dashboard')
        : redirect()->route('student.dashboard');
})->middleware(['auth'])->name('dashboard');

// ==========================================
// 1. Rute Khusus Admin
// ==========================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // Manajemen Pengguna
    Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');
    Route::post('/users/{user}/toggle-ready', [AdminController::class, 'toggleCounselorReady'])->name('users.toggle-ready');

    // Manajemen Kategori
    Route::get('/categories', [AdminController::class, 'categories'])->name('categories.index');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
    Route::put('/categories/{category}', [AdminController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminController::class, 'deleteCategory'])->name('categories.delete');

    // Monitoring & Manajemen Sesi Konseling
    Route::get('/sessions', [AdminController::class, 'sessions'])->name('sessions.index');
    Route::post('/sessions/{session}/reassign', [AdminController::class, 'reassignSession'])->name('sessions.reassign');
    Route::put('/sessions/{session}/status', [AdminController::class, 'updateSessionStatus'])->name('sessions.update-status');
    Route::delete('/sessions/{session}', [AdminController::class, 'deleteSession'])->name('sessions.delete');

    // Pengaturan Sistem & AI
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
});

// ==========================================
// 2. Rute Khusus Siswa (Bimbingan Konseling)
// ==========================================
Route::middleware(['auth', 'role:siswa'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'index'])->name('dashboard');

    // AI Pra-Konseling Triage Flow
    Route::get('/ai-consult', [AiCounselingController::class, 'showForm'])->name('ai-consult.form');
    Route::post('/ai-consult/analyze', [AiCounselingController::class, 'analyze'])->name('ai-consult.analyze');
    Route::post('/ai-consult/live-preview', [AiCounselingController::class, 'livePreview'])->name('ai-consult.live-preview');

    // Pemilihan Guru BK & Pembuatan Sesi
    Route::get('/select-counselor', [StudentController::class, 'selectCounselor'])->name('select-counselor');
    Route::post('/sessions/create', [StudentController::class, 'createSession'])->name('sessions.create');
});

// ==========================================
// 3. Rute Khusus Guru BK
// ==========================================
Route::middleware(['auth', 'role:guru_bk'])->prefix('counselor')->name('counselor.')->group(function () {
    Route::get('/dashboard', [CounselorController::class, 'index'])->name('dashboard');
    Route::post('/toggle-ready', [CounselorController::class, 'toggleReady'])->name('toggle-ready');
    Route::post('/sessions/{id}/accept', [CounselorController::class, 'acceptRequest'])->name('sessions.accept');
    Route::post('/sessions/{id}/complete', [CounselorController::class, 'completeSession'])->name('sessions.complete');
    Route::post('/sessions/{id}/reject', [CounselorController::class, 'rejectRequest'])->name('sessions.reject');
});

// ==========================================
// 4. User Profile
// ==========================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
