<?php

use App\Http\Controllers\PateintPrintController;
use App\Models\Letter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('/gate');
});

Route::get('/patients/{patient}/print', [PateintPrintController::class, 'print'])
    ->name('print.patient-overview')
    ->middleware(['auth']);


Route::get('/letters/{letter}/print', function (Letter $letter) {
    return view('print.letter', compact('letter'));
})->name('print.letter');

Route::get('/outgoings/verify/{issue}', function (string $issue) {
    $letter = Letter::where('issue_number', $issue)->firstOrFail();
    if ($letter->document_path && Storage::disk('public')->exists($letter->document_path)) {
        return response()->file(Storage::disk('public')->path($letter->document_path));
    }
    return view('print.letter', ['letter' => $letter]);
})->name('letters.verify');

