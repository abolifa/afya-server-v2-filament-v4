<?php

namespace App\Filament\Widgets;

use App\Models\Center;
use Filament\Widgets\ChartWidget;

class TopCentersOrders extends ChartWidget
{
    protected ?string $heading = 'أكثر المراكز طلباً للمعدات';

    protected ?string $height = 'h-full';

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $centersData = Center::withCount(['invoices' => function ($query) {
            $query->where('status', 'confirmed');
        }])
            ->orderByDesc('invoices_count')
            ->take(20)
            ->get();

        $labels = $centersData->pluck('name')->toArray();
        $data = $centersData->pluck('invoices_count')->toArray();

        $borderColor = '#3b82f6';
        $backgroundColor = 'rgba(59, 130, 246, 0.2)';

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'عدد الفواتير المؤكدة',
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
