<?php

use App\Http\Controllers\RegistrarController;
use App\Models\Document;
use Illuminate\Support\Facades\Route;

Route::get('/', function () 
{
    $documents = Document::all();
    return view('welcome', compact('documents'));
});
Route::post('/request-document', [App\Http\Controllers\RegistrarController::class, 'requestDocument']);
Route::get('/get-latest-queue-number', [RegistrarController::class, 'getLatestQueueNumber']);
Route::get('/kiosk-terminal', [RegistrarController::class, 'showKiosk'])->name('kiosk.terminal');
Route::get('/get-waiting-list', [RegistrarController::class, 'getWaitingList']);
Route::get('/get-queue-info', [RegistrarController::class, 'getQueueInfo']);
// Route::get('/print-queue/{queueNumber}', [RegistrarController::class, 'printQueueNumber']);