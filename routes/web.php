<?php

use App\Http\Controllers\PateintPrintController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('/patients/{patient}/print', [PateintPrintController::class, 'print'])
    ->name('print.patient-overview')
    ->middleware(['auth']);

