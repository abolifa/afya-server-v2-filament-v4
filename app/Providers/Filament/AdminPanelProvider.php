<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\LatestOrders;
use App\Filament\Widgets\LatestPendingAppointments;
use App\Filament\Widgets\LowStockProducts;
use App\Filament\Widgets\SystemState;
use App\Filament\Widgets\TopAppointmentPerCenter;
use App\Filament\Widgets\TopCentersOrders;
use App\Filament\Widgets\TopUsedDevices;
use App\Filament\Widgets\TopUsedProducts;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Exception;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

//use Filament\Pages\Dashboard;

class AdminPanelProvider extends PanelProvider
{
    /**
     * @throws Exception
     */
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Indigo,
            ])
            ->navigationGroups([
                NavigationGroup::make('إدارة المرضى')
                    ->icon('fas-shopping-cart')
                    ->collapsed(false),
                NavigationGroup::make('إدارة المخزون')
                    ->icon('fas-box-open')
                    ->collapsed(false),
                NavigationGroup::make('إدارة الموارد')
                    ->icon('fas-building')
                    ->collapsed(false),
                NavigationGroup::make('إدارة المشتريات')
                    ->icon('fas-shopping-basket')
                    ->collapsed(false),
                NavigationGroup::make('إدارة الحسابات')
                    ->icon('fas-users')
                    ->collapsed(false),
            ])
            ->font('Noto Kufi Arabic')
            ->profile()
            ->sidebarCollapsibleOnDesktop()
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->brandName('نظام عافية الطبي')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([])
            ->widgets([
                SystemState::class,
                LatestPendingAppointments::class,
                TopUsedProducts::class,
                TopAppointmentPerCenter::class,
                LowStockProducts::class,
                TopCentersOrders::class,
                TopUsedDevices::class,
                LatestOrders::class,

            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make(),
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
