<?php

namespace App\Filament\Resources\StockMovements\Schemas;

use App\Filament\Infolists\Components\TableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StockMovementInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make([
                    TextEntry::make('type')
                        ->label('نوع الحركة')
                        ->formatStateUsing(fn($state) => match ($state) {
                            'in' => 'وارد',
                            'out' => 'صادر',
                            'transfer' => 'تحويل',
                            default => $state,
                        })
                        ->color(fn($state) => match ($state) {
                            'in' => 'success',
                            'out' => 'danger',
                            'transfer' => 'warning',
                            default => 'secondary',
                        }),
                    TextEntry::make('actor.name')
                        ->label('بواسطة')
                        ->numeric(),
                    TextEntry::make('subject_type')
                        ->label('نوع الطلب')
                        ->formatStateUsing(fn($state) => match ($state) {
                            'App\Models\Invoice' => 'مشتريات',
                            'App\Models\TransferInvoice' => 'تحويل',
                            'App\Models\Order' => 'طلب مريض',
                        })
                        ->color(fn($state) => match ($state) {
                            'App\Models\Invoice' => 'success',
                            'App\Models\TransferInvoice' => 'warning',
                            'App\Models\Order' => 'dander',
                            default => 'secondary',
                        }),
                    TextEntry::make('subject_id')
                        ->label('رقم الطلب'),
                    TextEntry::make('fromCenter.name')
                        ->label('من المركز')
                        ->placeholder('-'),
                    TextEntry::make('toCenter.name')
                        ->label('إلى المركز')
                        ->placeholder('-'),
                    TextEntry::make('patient.name')
                        ->label('إلى المريض')
                        ->placeholder('-'),
                    TextEntry::make('supplier.name')
                        ->label('المورد')
                        ->placeholder('-'),
                    TextEntry::make('created_at')
                        ->label('تاريخ الإنشاء')
                        ->dateTime(),
                ])->columnSpanFull()
                    ->columns(3),

                Section::make([
                    TableEntry::make('items')
                        ->label('الأصناف'),
                ])->columnSpanFull(),
            ]);
    }
}
