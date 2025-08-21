<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use Filament\Widgets\ChartWidget;

class TopUsedProducts extends ChartWidget
{
    protected ?string $heading = 'الأصناف الأكثر استخدامًا';

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getData(): array
    {
        $orders = Order::with('items')->where('status', 'confirmed')->get();
        $productsData = $orders
            ->flatMap(fn($order) => $order->items)
            ->groupBy('product_id')
            ->map(fn($items, $productId) => [
                'product_id' => $productId,
                'quantity' => $items->sum('quantity'),
                'orders_count' => $items->count(),
            ])
            ->sortByDesc('quantity')
            ->take(20)
            ->values();
        $productIds = $productsData->pluck('product_id')->toArray();
        $products = Product::whereIn('id', $productIds)->pluck('name', 'id');

        $labels = $productsData->map(fn($item) => $products[$item['product_id']] ?? 'Unknown')->toArray();
        $data = $productsData->pluck('quantity')->toArray();
        $backgroundColors = array_map(function ($name) {
            $hash = crc32($name);
            return sprintf('#%06X', $hash & 0xFFFFFF);
        }, $labels);

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'الكمية المستخدمة',
                    'data' => $data,
                    'backgroundColor' => $backgroundColors,
                ],
            ],
        ];
    }

}
