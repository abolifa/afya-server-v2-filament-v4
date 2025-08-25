<?php

namespace App\Filament\Resources\WhiteLists;

use App\Filament\Resources\WhiteLists\Pages\CreateWhiteList;
use App\Filament\Resources\WhiteLists\Pages\EditWhiteList;
use App\Filament\Resources\WhiteLists\Pages\ListWhiteLists;
use App\Filament\Resources\WhiteLists\Schemas\WhiteListForm;
use App\Filament\Resources\WhiteLists\Tables\WhiteListsTable;
use App\Models\WhiteList;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class WhiteListResource extends Resource
{
    protected static ?string $model = WhiteList::class;

    protected static ?string $label = 'قائمة';
    protected static ?string $pluralLabel = 'القائمة البيضاء';

    protected static string|BackedEnum|null $navigationIcon = 'fas-shield-alt';

    protected static string|null|UnitEnum $navigationGroup = 'إدارة الحسابات';

    public static function form(Schema $schema): Schema
    {
        return WhiteListForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WhiteListsTable::configure($table);
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
            'index' => ListWhiteLists::route('/'),
            'create' => CreateWhiteList::route('/create'),
            'edit' => EditWhiteList::route('/{record}/edit'),
        ];
    }
}
