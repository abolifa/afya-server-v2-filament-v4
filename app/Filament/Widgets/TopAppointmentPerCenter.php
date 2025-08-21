<?php

namespace App\Filament\Widgets;

use App\Models\Center;
use Filament\Widgets\ChartWidget;

class TopAppointmentPerCenter extends ChartWidget
{
    protected ?string $heading = 'أكثر المراكز تنفيذاً للمواعيد';

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getData(): array
    {
        $centersData = Center::withCount('appointments')
            ->orderByDesc('appointments_count')
            ->take(20)
            ->get();

        $labels = $centersData->pluck('name')->toArray();
        $data = $centersData->pluck('appointments_count')->toArray();

        // Generate stable colors for each slice
        $backgroundColors = array_map(function ($name) {
            $hash = crc32($name);
            return sprintf('#%06X', $hash & 0xFFFFFF);
        }, $labels);

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'عدد المواعيد',
                    'data' => $data,
                    'backgroundColor' => $backgroundColors,
                ],
            ],
        ];
    }
}
