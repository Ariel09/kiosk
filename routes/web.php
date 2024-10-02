<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () 
{
    return view('welcome');
});
Route::post('/request-document', [App\Http\Controllers\RegistrarController::class, 'requestDocument']);