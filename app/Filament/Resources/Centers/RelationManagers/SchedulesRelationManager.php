<?php

namespace App\Filament\Resources\Centers\RelationManagers;

use App\Filament\Forms\Components\BooleanField;
use Carbon\Carbon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class SchedulesRelationManager extends RelationManager
{
    protected static string $relationship = 'schedules';

    protected static ?string $label = 'يوم';
    protected static ?string $title = 'جدول العمل';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('day')
                    ->options([
                        'saturday' => 'السبت',
                        'sunday' => 'الأحد',
                        'monday' => 'الإثنين',
                        'tuesday' => 'الثلاثاء',
                        'wednesday' => 'الأربعاء',
                        'thursday' => 'الخميس',
                        'friday' => 'الجمعة',
                    ])
                    ->columnSpanFull()
                    ->default(fn() => strtolower(Carbon::now()->format('l')))
                    ->required(),
                TimePicker::make('start_time')
                    ->label('ساعة البدء')
                    ->default('9:00 AM')
                    ->displayFormat('h:i A')
                    ->required(),
                TimePicker::make('end_time')
                    ->label('ساعة الانتهاء')
                    ->default('5:00 PM')
                    ->displayFormat('h:i A')
                    ->required(),
                BooleanField::make('is_active')
                    ->label('نشط')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('day')
                    ->label('اليوم')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn($state) => match ($state) {
                        'saturday' => 'السبت',
                        'sunday' => 'الأحد',
                        'monday' => 'الإثنين',
                        'tuesday' => 'الثلاثاء',
                        'wednesday' => 'الأربعاء',
                        'thursday' => 'الخميس',
                        'friday' => 'الجمعة',
                        default => $state,
                    }),
                TextColumn::make('start_time')
                    ->label('ساعة البدء')
                    ->time('h:i A')
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('end_time')
                    ->label('ساعة الانتهاء')
                    ->alignCenter()
                    ->time('h:i A')
                    ->sortable(),
                ToggleColumn::make('is_active')
                    ->alignCenter()
                    ->label('نشط'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
//                AssociateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
//                DissociateAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
//                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
