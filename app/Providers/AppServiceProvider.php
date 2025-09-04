<?php

namespace App\Providers;

use App\Http\Responses\LogoutResponse;
use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Prescription;
use App\Models\TransferInvoice;
use App\Observers\AppointmentObserver;
use App\Observers\OrderObserver;
use App\Observers\PrescriptionObserver;
use App\Observers\StockMovementObserver;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public $singletons = [
        \Filament\Auth\Http\Responses\Contracts\LogoutResponse::class => LogoutResponse::class,
    ];

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
            'slate' => Color::Slate,
        ]);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Invoice::observe(StockMovementObserver::class);
        TransferInvoice::observe(StockMovementObserver::class);
        Order::observe(OrderObserver::class);
        Appointment::observe(AppointmentObserver::class);
        Prescription::observe(PrescriptionObserver::class);
    }
}
