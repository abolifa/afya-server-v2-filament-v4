<?php

namespace App\Filament\Resources\Centers;

use App\Filament\Resources\Centers\Pages\CreateCenter;
use App\Filament\Resources\Centers\Pages\EditCenter;
use App\Filament\Resources\Centers\Pages\ListCenters;
use App\Filament\Resources\Centers\Pages\ViewCenter;
use App\Filament\Resources\Centers\RelationManagers\SchedulesRelationManager;
use App\Filament\Resources\Centers\Schemas\CenterForm;
use App\Filament\Resources\Centers\Schemas\CenterInfolist;
use App\Filament\Resources\Centers\Tables\CentersTable;
use App\Models\Center;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class CenterResource extends Resource
{
    protected static ?string $model = Center::class;
    protected static string|BackedEnum|null $navigationIcon = "fas-building-ngo";

    protected static ?string $label = 'مركز';
    protected static ?string $pluralLabel = 'المراكز';

    protected static string|null|UnitEnum $navigationGroup = 'إدارة الموارد';


    public static function form(Schema $schema): Schema
    {
        return CenterForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CenterInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CentersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            SchedulesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCenters::route('/'),
            'create' => CreateCenter::route('/create'),
            'view' => ViewCenter::route('/{record}'),
            'edit' => EditCenter::route('/{record}/edit'),
        ];
    }
}
