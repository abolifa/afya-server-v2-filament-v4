<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public static string|null|BackedEnum $navigationIcon = 'fas-tachometer-alt';
}
