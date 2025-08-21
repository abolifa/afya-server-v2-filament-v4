<?php

namespace App\Providers;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\TransferInvoice;
use App\Observers\StockMovementObserver;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        FilamentColor::register([
            'rose' => Color::Rose,
            'emerald' => Color::Emerald,
            'sky' => Color::Sky,
            'amber' => Color::Amber,
            'indigo' => Color::Indigo,
            'purple' => Color::Purple,
            'gold' => Color::Yellow,
        ]);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Invoice::observe(StockMovementObserver::class);
        TransferInvoice::observe(StockMovementObserver::class);
        Order::observe(StockMovementObserver::class);
    }
}
