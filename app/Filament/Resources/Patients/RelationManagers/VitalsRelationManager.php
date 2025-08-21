<?php

namespace App\Filament\Resources\Patients\RelationManagers;

use App\Models\Vital;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VitalsRelationManager extends RelationManager
{
    protected static string $relationship = 'vitals';
    protected static ?string $title = 'المؤشرات الحيوية';
    protected static ?string $pluralLabel = "مؤشرات";
    protected static ?string $label = "مؤشر";

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('doctor_id')
                    ->relationship('doctor', 'name'),
                DatePicker::make('recorded_at')
                    ->label('تاريخ التسجيل')
                    ->required()
                    ->displayFormat('d/m/Y')
                    ->default(now()),
                TextInput::make('weight')
                    ->label('الوزن')
                    ->numeric(),
                TextInput::make('systolic')
                    ->label('الضغط الانقباضي')
                    ->numeric(),
                TextInput::make('diastolic')
                    ->label('الضغط الانبساطي')
                    ->numeric(),
                TextInput::make('heart_rate')
                    ->label('معدل ضربات القلب')
                    ->numeric(),
                TextInput::make('temperature')
                    ->label('درجة الحرارة')
                    ->numeric(),
                TextInput::make('oxygen_saturation')
                    ->label('تشبع الأكسجين')
                    ->numeric(),
                TextInput::make('sugar_level')
                    ->label('مستوى السكر')
                    ->numeric(),
                Textarea::make('notes')
                    ->label('ملاحظات')
                    ->rows(3)
                    ->columnSpanFull()
                    ->maxLength(500),
            ])->columns(4);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('doctor.name')
                    ->searchable(),
                TextColumn::make('recorded_at')
                    ->label('تاريخ التسجيل')
                    ->date('d/m/Y'),

                TextColumn::make('weight')
                    ->label('الوزن')
                    ->numeric()
                    ->alignCenter()
                    ->color(fn($state) => match (true) {
                        $state >= 50 && $state <= 100 => 'success',
                        $state > 100 && $state <= 120 => 'warning',
                        default => 'danger',
                    }),

                TextColumn::make('blood_pressure')
                    ->label('ضغط الدم')
                    ->getStateUsing(fn(Vital $record): string => "$record->systolic/$record->diastolic")
                    ->alignCenter()
                    ->color(fn(string $state, Vital $record): string => match (true) {
                        $record->systolic < 120 && $record->diastolic < 80 => 'success',
                        $record->systolic < 140 && $record->diastolic < 90 => 'warning',
                        default => 'danger',
                    }),

                TextColumn::make('heart_rate')
                    ->label('معدل ضربات القلب')
                    ->numeric()
                    ->alignCenter()
                    ->suffix(' p/m ')
                    ->color(fn($state) => match (true) {
                        $state >= 60 && $state <= 100 => 'success',
                        $state > 100 && $state <= 120 => 'warning',
                        default => 'danger',
                    }),

                TextColumn::make('temperature')
                    ->label('درجة الحرارة')
                    ->numeric()
                    ->alignCenter()
                    ->suffix('°C')
                    ->color(fn($state) => match (true) {
                        $state >= 36 && $state <= 37.5 => 'success',
                        $state > 37.5 && $state <= 38.5 => 'warning',
                        default => 'danger',
                    }),

                TextColumn::make('oxygen_saturation')
                    ->label('تشبع الأكسجين')
                    ->numeric()
                    ->alignCenter()
                    ->suffix('%')
                    ->color(fn($state) => match (true) {
                        $state >= 95 => 'success',
                        $state >= 90 => 'warning',
                        default => 'danger',
                    }),

                TextColumn::make('sugar_level')
                    ->label('مستوى السكر')
                    ->numeric()
                    ->alignCenter()
                    ->suffix(' mg/dL')
                    ->color(fn($state) => match (true) {
                        $state >= 70 && $state <= 140 => 'success',
                        $state > 140 && $state <= 200 => 'warning',
                        default => 'danger',
                    }),
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
