<?php

namespace App\Filament\Site\Resources\Awarenesses;

use App\Filament\Site\Resources\Awarenesses\Pages\CreateAwareness;
use App\Filament\Site\Resources\Awarenesses\Pages\EditAwareness;
use App\Filament\Site\Resources\Awarenesses\Pages\ListAwarenesses;
use App\Filament\Site\Resources\Awarenesses\Schemas\AwarenessForm;
use App\Filament\Site\Resources\Awarenesses\Tables\AwarenessesTable;
use App\Models\Awareness;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class AwarenessResource extends Resource
{
    protected static ?string $model = Awareness::class;

    protected static string|null|BackedEnum $navigationIcon = 'gmdi-follow-the-signs';

    protected static ?string $label = 'توعية';
    protected static ?string $pluralLabel = 'التوعيات';

    public static function form(Schema $schema): Schema
    {
        return AwarenessForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AwarenessesTable::configure($table);
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
            'index' => ListAwarenesses::route('/'),
            'create' => CreateAwareness::route('/create'),
            'edit' => EditAwareness::route('/{record}/edit'),
        ];
    }
}
