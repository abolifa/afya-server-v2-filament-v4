<?php

namespace App\Filament\Site\Resources\Announcements;

use App\Filament\Site\Resources\Announcements\Pages\CreateAnnouncement;
use App\Filament\Site\Resources\Announcements\Pages\EditAnnouncement;
use App\Filament\Site\Resources\Announcements\Pages\ListAnnouncements;
use App\Filament\Site\Resources\Announcements\Schemas\AnnouncementForm;
use App\Filament\Site\Resources\Announcements\Tables\AnnouncementsTable;
use App\Models\Announcement;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class AnnouncementResource extends Resource
{
    protected static ?string $model = Announcement::class;


    protected static string|null|BackedEnum $navigationIcon = 'gmdi-bolt-o';

    protected static ?string $label = 'إعلان';
    protected static ?string $pluralLabel = 'الإعلانات';

    public static function form(Schema $schema): Schema
    {
        return AnnouncementForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AnnouncementsTable::configure($table);
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
            'index' => ListAnnouncements::route('/'),
            'create' => CreateAnnouncement::route('/create'),
            'edit' => EditAnnouncement::route('/{record}/edit'),
        ];
    }
}
