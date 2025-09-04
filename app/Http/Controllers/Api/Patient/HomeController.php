<?php

namespace App\Http\Controllers\Api\Patient;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomeController
{
    public function index(Request $request): JsonResponse
    {
        $patient = $request->user();
        $appointments_count = $patient->appointments()->count();
        $pending_appointments_count = $patient->appointments()->where('status', 'pending')->count();
        $orders_count = $patient->orders()->withoutGlobalScopes()->count();
        $prescriptions_count = $patient->prescriptions()->count();
        $appointments = $patient->appointments()->with('center', 'doctor')->latest()->take(5)->get();
        $orders = $patient->orders()->with('center', 'items')->withoutGlobalScopes()->latest()->take(5)->get();
        $prescriptions = $patient->prescriptions()->latest()->take(5)->get();

        return response()->json([
            'appointments_count' => $appointments_count,
            'orders_count' => $orders_count,
            'prescriptions_count' => $prescriptions_count,
            'pending_appointments_count' => $pending_appointments_count,
            'appointments' => $appointments,
            'orders' => $orders,
            'prescriptions' => $prescriptions,
        ]);
    }
}
