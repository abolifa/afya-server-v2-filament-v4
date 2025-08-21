<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use App\Models\Center;
use App\Models\Order;
use App\Models\Patient;
use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;

class SystemState extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalCenters = Center::all()->count();
        $totalAppointments = Appointment::all()->count();
        $totalPatients = Patient::all()->count();
        $totalDoctors = User::where('doctor', true)->where('active', true)->count();
        $totalOrders = Order::where('status', 'confirmed')->count();
        $totalProducts = Product::count();

        return [
            StatsOverviewWidget\Stat::make('المراكز', $totalCenters)
                ->color('success')
                ->icon('fas-building-ngo')
                ->description('إجمالي المراكز المسجلة'),
            StatsOverviewWidget\Stat::make('المواعيد', $totalAppointments)
                ->color('rose')
                ->icon('fas-calendar-check')
                ->description('إجمالي المواعيد'),
            StatsOverviewWidget\Stat::make('المرضى', $totalPatients)
                ->color('success')
                ->icon('fas-user-injured')
                ->description('إجمالي المرضى المسجلين'),
            StatsOverviewWidget\Stat::make('الأطباء', $totalDoctors)
                ->color('warning')
                ->icon('fas-user-doctor')
                ->description('إجمالي الأطباء النشطين'),
            StatsOverviewWidget\Stat::make('الطلبات', $totalOrders)
                ->color('purple')
                ->icon('fas-shopping-cart')
                ->description('إجمالي الطلبات المنفذة'),
            StatsOverviewWidget\Stat::make('الأصناف', $totalProducts)
                ->color('gold')
                ->icon('fas-boxes')
                ->description('إجمالي الأصناف المسجلة'),
        ];
    }
}
