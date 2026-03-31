<?php

use App\Http\Controllers\AgendaController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\InvitationTemplateController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicAgendaController;
use App\Http\Controllers\SignerController;
use Illuminate\Support\Facades\Route;

// Public agenda list
Route::get('/', [PublicAgendaController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::resource('participants', ParticipantController::class);
    Route::resource('signers', SignerController::class);
    Route::resource('templates', InvitationTemplateController::class);
    Route::resource('agendas', AgendaController::class);
    Route::get('agendas/{agenda}/pdf', [AgendaController::class, 'generatePdf'])->name('agendas.pdf');
});

// Public attendance routes
Route::get('/absen/{agenda}', [AttendanceController::class, 'show'])->name('attendance.show');
Route::post('/absen/{agenda}/sign', [AttendanceController::class, 'sign'])->name('attendance.sign');

require __DIR__ . '/auth.php';
