<?php

namespace App\Filament\Site\Resources\Complaints;

use App\Filament\Site\Resources\Complaints\Pages\ListComplaints;
use App\Filament\Site\Resources\Complaints\Schemas\ComplaintsInfolist;
use App\Filament\Site\Resources\Complaints\Tables\ComplaintsTable;
use App\Models\Complaint;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ComplaintResource extends Resource
{
    protected static ?string $model = Complaint::class;


    protected static string|null|BackedEnum $navigationIcon = 'gmdi-message-o';

    protected static ?string $pluralLabel = 'الشكاوى';
    protected static ?string $label = 'شكوى';


    public static function table(Table $table): Table
    {
        return ComplaintsTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ComplaintsInfolist::configure($schema);
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
            'index' => ListComplaints::route('/'),
            'view' => Pages\ViewComplaint::route('/{record}'),
        ];
    }
}
