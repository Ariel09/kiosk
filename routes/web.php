<?php

use App\Http\Controllers\RegistrarController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () 
{
    return view('welcome');
});
Route::post('/request-document', [App\Http\Controllers\RegistrarController::class, 'requestDocument']);
Route::get('/get-latest-queue-number', [RegistrarController::class, 'getLatestQueueNumber']);
Route::get('/kiosk-terminal', [RegistrarController::class, 'showKiosk'])->name('kiosk.terminal');
Route::get('/get-waiting-list', [RegistrarController::class, 'getWaitingList']);
Route::get('/get-queue-info', [RegistrarController::class, 'getQueueInfo']);
// Route::get('/print-queue/{queueNumber}', [RegistrarController::class, 'printQueueNumber']);