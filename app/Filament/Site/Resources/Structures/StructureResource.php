<?php

namespace App\Filament\Site\Resources\Structures;

use App\Filament\Site\Resources\Structures\Pages\CreateStructure;
use App\Filament\Site\Resources\Structures\Pages\EditStructure;
use App\Filament\Site\Resources\Structures\Pages\ListStructures;
use App\Filament\Site\Resources\Structures\Schemas\StructureForm;
use App\Filament\Site\Resources\Structures\Tables\StructuresTable;
use App\Models\Structure;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class StructureResource extends Resource
{
    protected static ?string $model = Structure::class;

    protected static string|null|BackedEnum $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $label = 'نقطة';
    protected static ?string $pluralLabel = 'الهيكل التنظيمي';


    public static function form(Schema $schema): Schema
    {
        return StructureForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StructuresTable::configure($table);
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
            'index' => ListStructures::route('/'),
            'create' => CreateStructure::route('/create'),
            'edit' => EditStructure::route('/{record}/edit'),
        ];
    }
}
