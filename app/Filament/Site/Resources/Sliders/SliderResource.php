<?php

namespace App\Filament\Site\Resources\Sliders;

use App\Filament\Site\Resources\Sliders\Pages\CreateSlider;
use App\Filament\Site\Resources\Sliders\Pages\EditSlider;
use App\Filament\Site\Resources\Sliders\Pages\ListSliders;
use App\Filament\Site\Resources\Sliders\Schemas\SliderForm;
use App\Filament\Site\Resources\Sliders\Tables\SlidersTable;
use App\Models\Slider;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class SliderResource extends Resource
{
    protected static ?string $model = Slider::class;

    protected static ?string $label = 'شريحة';
    protected static ?string $pluralLabel = 'الشرائح';

    protected static string|null|BackedEnum $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return SliderForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SlidersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSliders::route('/'),
            'create' => CreateSlider::route('/create'),
            'edit' => EditSlider::route('/{record}/edit'),
        ];
    }
}
