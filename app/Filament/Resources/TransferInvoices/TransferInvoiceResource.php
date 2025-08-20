<?php

namespace App\Filament\Resources\TransferInvoices;

use App\Filament\Resources\TransferInvoices\Pages\CreateTransferInvoice;
use App\Filament\Resources\TransferInvoices\Pages\EditTransferInvoice;
use App\Filament\Resources\TransferInvoices\Pages\ListTransferInvoices;
use App\Filament\Resources\TransferInvoices\Pages\ViewTransferInvoice;
use App\Filament\Resources\TransferInvoices\Schemas\TransferInvoiceForm;
use App\Filament\Resources\TransferInvoices\Schemas\TransferInvoiceInfolist;
use App\Filament\Resources\TransferInvoices\Tables\TransferInvoicesTable;
use App\Models\TransferInvoice;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class TransferInvoiceResource extends Resource
{
    protected static ?string $model = TransferInvoice::class;

    protected static ?string $label = 'تحويل';
    protected static ?string $pluralLabel = 'تحويل المخزون';

    protected static string|BackedEnum|null $navigationIcon = 'gmdi-move-up-o';

    public static function form(Schema $schema): Schema
    {
        return TransferInvoiceForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TransferInvoiceInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TransferInvoicesTable::configure($table);
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
            'index' => ListTransferInvoices::route('/'),
            'create' => CreateTransferInvoice::route('/create'),
            'view' => ViewTransferInvoice::route('/{record}'),
            'edit' => EditTransferInvoice::route('/{record}/edit'),
        ];
    }
}
