<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Support\Facades\View;
use Mpdf\Mpdf;

class PateintPrintController
{
    public function print(Patient $patient)
    {
        $data = [
            'patient' => $patient,
        ];
        $html = View::make('print.patient-overview', $data)->render();
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
        $mpdf = new Mpdf($config);
        $mpdf->WriteHTML($html);
        return response($mpdf->Output('patient-overview.pdf', 'I'))
            ->header('Content-Type', 'application/pdf');
    }
}
