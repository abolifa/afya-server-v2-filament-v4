<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\View\View;
use niklasravnsborg\LaravelPdf\Facades\Pdf;

class PateintPrintController
{
    public function print(Patient $patient): View
    {
        $data = [
            'patient' => $patient,
        ];

        $config = [
            'format' => 'A4',
            'orientation' => 'P',
            'margin_top' => 10,
            'margin_right' => 10,
            'margin_bottom' => 10,
            'margin_left' => 10,
            'mode' => 'utf-8',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'dpi' => 100,
        ];

        $pdf = PDF::loadView('print.patient-overview', $data, [], $config);

        return $pdf->stream('print.patient-overview');
    }
}
