<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                ImageEntry::make('image')
                    ->label('الصورة')
                    ->imageSize(80)
                    ->disk('public'),
                TextEntry::make('name')
                    ->label('الإسم'),
                TextEntry::make('type')
                    ->label('النوع')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'medicine' => 'دواء',
                        'equipment' => 'معدات',
                        'service' => 'خدمة',
                        'other' => 'أخرى',
                    })
                    ->color(fn($state) => match ($state) {
                        'medicine' => 'primary',
                        'equipment' => 'secondary',
                        'service' => 'success',
                        'other' => 'warning',
                    }),
                TextEntry::make('expiry_date')
                    ->label('الصلاحية')
                    ->color(function ($state) {
                        if (!$state) {
                            return null;
                        } elseif ($state < now()) {
                            return 'danger';
                        } elseif ($state < now()->addDays(30)) {
                            return 'warning';
                        } elseif ($state < now()->addDays(90)) {
                            return 'warning';
                        }
                        return 'success';
                    })
                    ->date('d/m/Y'),
                TextEntry::make('alert_threshold')
                    ->label('حد التنبيه')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->label('تاريخ التحديث')
                    ->dateTime(),
            ])->columns(3);
    }
}
