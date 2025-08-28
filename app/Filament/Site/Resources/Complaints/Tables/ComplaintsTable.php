<?php

namespace App\Filament\Site\Resources\Complaints\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ComplaintsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('الاسم')
                    ->placeholder('غير معروف'),
                TextColumn::make('phone')
                    ->label('الهاتف')
                    ->placeholder('غير معروف')
                    ->alignCenter(),
                TextColumn::make('message')
                    ->label('الرسالة')
                    ->limit(50)
                    ->alignCenter()
                    ->placeholder('لا توجد رسالة'),
                TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->alignCenter()
                    ->date('d/m/Y'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
