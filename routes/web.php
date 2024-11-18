<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\RegistrarController;
use App\Models\Document;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $documents = Document::all();
    $student = auth()->user()?->student; // Access the related student info
    return view('welcome', compact('documents', 'student'));
})->middleware('auth')->name('welcome');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::post('/request-document', [App\Http\Controllers\RegistrarController::class, 'requestDocument']);
Route::get('/get-latest-queue-number', [RegistrarController::class, 'getLatestQueueNumber']);
Route::get('/kiosk-terminal', [RegistrarController::class, 'showKiosk'])->name('kiosk.terminal');
Route::get('/get-waiting-list', [RegistrarController::class, 'getWaitingList']);
Route::post('/submit-quantities', [RegistrarController::class, 'submitQuantities']);
Route::get('/get-queue-info', [RegistrarController::class, 'getQueueInfo']);
// Route::get('/print-queue/{queueNumber}', [RegistrarController::class, 'printQueueNumber']);

Route::post('/emails/send', [EmailController::class, 'sendEmail'])->name('emails.send');
