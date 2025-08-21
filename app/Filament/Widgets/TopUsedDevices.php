<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Filament\Widgets\ChartWidget;

class TopUsedDevices extends ChartWidget
{
    protected ?string $heading = 'أكثر الأجهزة استخدامًا';

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        // Get all appointments with device and calculate total hours
        $appointments = Appointment::with('device')->get();

        // Group appointments by device_id and sum total_hours
        $devicesData = $appointments
            ->filter(fn($appointment) => $appointment->device_id) // skip null devices
            ->groupBy('device_id')
            ->map(fn($appointments, $deviceId) => [
                'device_id' => $deviceId,
                'device_name' => $appointments->first()->device?->name ?? 'Unknown',
                'total_hours' => $appointments->sum('total_hours'),
            ])
            ->sortByDesc('total_hours')
            ->take(20) // top 20 devices
            ->values();

        $labels = $devicesData->pluck('device_name')->toArray();
        $data = $devicesData->pluck('total_hours')->toArray();

        // Optional: stable line color
        $borderColor = '#f97316'; // orange
        $backgroundColor = 'rgba(249, 115, 22, 0.2)';

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'إجمالي الساعات',
                    'data' => $data,
                    'borderColor' => $borderColor,
                    'backgroundColor' => $backgroundColor,
                    'fill' => true,
                    'tension' => 0.3, // smooth curve
                ],
            ],
        ];
    }
}
