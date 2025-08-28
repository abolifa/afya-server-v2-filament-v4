<?php

namespace App\Filament\Site\Resources\Sliders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class SlidersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')
                    ->label('نوع الشريحة')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'image' => 'صورة',
                        'url' => 'رابط',
                        'post' => 'منشور',
                        default => 'غير معروف',
                    })
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'image' => 'success',
                        'url' => 'warning',
                        'post' => 'info',
                        default => 'gray',
                    })
                    ->sortable()
                    ->searchable(),
                ImageColumn::make('image')
                    ->label('الصورة')
                    ->disk('public')
                    ->visibility('public')
                    ->alignCenter(),
                TextColumn::make('post.title')
                    ->label('المنشور')
                    ->placeholder('-')
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('url')
                    ->label('الرابط')
                    ->placeholder('-')
                    ->alignCenter(),
                ToggleColumn::make('active')
                    ->label('نشط')
                    ->alignCenter(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
