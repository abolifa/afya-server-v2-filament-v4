<?php

namespace App\Providers\Filament;

use App\Filament\Archive\Widgets\LatestLetters;
use App\Filament\Archive\Widgets\SystemState;
use Filament\Actions\Action;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class ArchivePanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('archive')
            ->path('archive')
            ->font('Noto Kufi Arabic')
            ->userMenuItems([
                Action::make('change')
                    ->label('ذهاب للرئيسية')
                    ->icon('heroicon-o-home')
                    ->url('/'),
            ])
            ->colors([
                'primary' => Color::Green,
            ])
            ->brandName('نظام الأرشيف والمراسلة')
            ->maxContentWidth('7xl')
            ->profile()
            ->login()
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->discoverResources(in: app_path('Filament/Archive/Resources'), for: 'App\Filament\Archive\Resources')
            ->discoverPages(in: app_path('Filament/Archive/Pages'), for: 'App\Filament\Archive\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->widgets([
                SystemState::class,
                LatestLetters::class,
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
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
