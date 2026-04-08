<?php

use App\Http\Controllers\AgendaController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\BankSoalController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicAgendaController;
use App\Http\Controllers\PublicAgendaInputController;
use App\Http\Controllers\PublicQuizController;
use App\Http\Controllers\RoomController;
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
    Route::resource('employees', EmployeeController::class);
    Route::resource('rooms', RoomController::class);
    Route::resource('agendas', AgendaController::class);
    Route::get('agendas/{agenda}/export-pdf', [AgendaController::class, 'exportPdf'])->name('agendas.export-pdf');
    Route::resource('bank-soals', BankSoalController::class);
});

// Public attendance routes
Route::get('/absen/{agenda}', [AttendanceController::class, 'show'])->name('attendance.show');
Route::post('/absen/{agenda}/sign', [AttendanceController::class, 'sign'])->name('attendance.sign');

// Public quiz routes
Route::get('/absen/{agenda}/quiz', [PublicQuizController::class, 'show'])->name('attendance.quiz');
Route::post('/absen/{agenda}/quiz', [PublicQuizController::class, 'store'])->name('attendance.quiz.store');

// Public agenda input (crowdsourced notes + images)
Route::get('/agenda/{agenda}/input', [PublicAgendaInputController::class, 'show'])->name('agenda.input');
Route::post('/agenda/{agenda}/input/note', [PublicAgendaInputController::class, 'storeNote'])->name('agenda.input.note');
Route::post('/agenda/{agenda}/input/image', [PublicAgendaInputController::class, 'storeImage'])->name('agenda.input.image');

require __DIR__ . '/auth.php';
