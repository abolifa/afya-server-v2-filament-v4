<?php

use App\Http\Controllers\PateintPrintController;
use App\Models\Letter;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('/gate');
});

Route::get('/patients/{patient}/print', [PateintPrintController::class, 'print'])
    ->name('print.patient-overview')
    ->middleware(['auth']);


Route::get('/letters/{letter}/print', function (Letter $letter) {
    return view('print.letter', compact('letter'));
})->name('print.letter');

