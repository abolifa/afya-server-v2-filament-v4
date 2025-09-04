<?php

namespace App\Filament\Archive\Resources\Letters;

use App\Filament\Archive\Resources\Letters\Pages\CreateLetter;
use App\Filament\Archive\Resources\Letters\Pages\EditLetter;
use App\Filament\Archive\Resources\Letters\Pages\ListLetters;
use App\Filament\Archive\Resources\Letters\Pages\ViewLetter;
use App\Filament\Archive\Resources\Letters\Schemas\LetterForm;
use App\Filament\Archive\Resources\Letters\Schemas\LetterInfolist;
use App\Filament\Archive\Resources\Letters\Tables\LettersTable;
use App\Models\Letter;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LetterResource extends Resource
{
    protected static ?string $model = Letter::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::DocumentArrowUp;

    protected static ?string $label = "رسالة";
    protected static ?string $pluralLabel = "الرسائل";

    public static function form(Schema $schema): Schema
    {
        return LetterForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LetterInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LettersTable::configure($table);
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
            'index' => ListLetters::route('/'),
            'create' => CreateLetter::route('/create'),
            'view' => ViewLetter::route('/{record}'),
            'edit' => EditLetter::route('/{record}/edit'),
        ];
    }
}
