<?php

namespace App\Filament\Resources\TransferInvoices\Schemas;

use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class TransferInvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('from_center_id')
                    ->relationship('fromCenter', 'name')
                    ->required(),
                Select::make('to_center_id')
                    ->relationship('toCenter', 'name')
                    ->required(),
                Select::make('status')
                    ->options(['pending' => 'Pending', 'confirmed' => 'Confirmed', 'cancelled' => 'Cancelled'])
                    ->default('pending')
                    ->required(),
            ]);
    }
}
