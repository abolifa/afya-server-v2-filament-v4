<?php

namespace App\Filament\Site\Resources\Awarenesses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Support\Arr;

class AwarenessesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('العنوان')
                    ->searchable(),
                TextColumn::make('attachments_count')
                    ->label('عدد المرفقات')
                    ->getStateUsing(function ($record) {
                        $items = collect($record->attachments ?? []);
                        return $items->filter(function ($row) {
                            if (!is_array($row)) return false;
                            $title = trim((string)Arr::get($row, 'title', ''));
                            $content = trim((string)Arr::get($row, 'content', ''));
                            $image = (string)Arr::get($row, 'image', '');
                            return $title !== '' || $content !== '' || $image !== '';
                        })->count();
                    })
                    ->alignCenter()
                    ->sortable()
                    ->badge(),
                ToggleColumn::make('active')
                    ->alignCenter()
                    ->label('نشط'),
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
