<?php

namespace App\Filament\Archive\Resources\Contacts;

use App\Filament\Archive\Resources\Contacts\Pages\CreateContact;
use App\Filament\Archive\Resources\Contacts\Pages\EditContact;
use App\Filament\Archive\Resources\Contacts\Pages\ListContacts;
use App\Filament\Archive\Resources\Contacts\Pages\ViewContact;
use App\Filament\Archive\Resources\Contacts\Schemas\ContactForm;
use App\Filament\Archive\Resources\Contacts\Schemas\ContactInfolist;
use App\Filament\Archive\Resources\Contacts\Tables\ContactsTable;
use App\Models\Contact;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static string|BackedEnum|null $navigationIcon = 'gmdi-contact-emergency-tt';

    protected static ?string $label = "جهة اتصال";
    protected static ?string $pluralLabel = "جهات اتصال";

    public static function form(Schema $schema): Schema
    {
        return ContactForm::configure($schema);
    }


    public static function table(Table $table): Table
    {
        return ContactsTable::configure($table);
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
            'index' => ListContacts::route('/'),
        ];
    }
}
