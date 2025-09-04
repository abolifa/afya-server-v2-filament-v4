<?php

namespace App\Filament\Archive\Resources\Documents\Schemas;

use App\Filament\Infolists\Components\ViewPdf;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;

class DocumentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make('المعلومات الأساسية')
                    ->schema([
                        TextEntry::make('issue_number')
                            ->label('الرقم الإشاري'),
                        TextEntry::make('type')
                            ->label('نوع المستند')
                            ->formatStateUsing(fn($state) => match ($state) {
                                'letter' => 'خطاب',
                                'archive' => 'أرشيف',
                                'report' => 'تقرير',
                                'other' => 'أخرى',
                            }),
                        TextEntry::make('issue_date')
                            ->label('تاريخ الإصدار')
                            ->date(),
                        TextEntry::make('fromContact.name')
                            ->label('من جهة الاتصال')
                            ->placeholder('-'),
                        TextEntry::make('fromCenter.name')
                            ->label('من مركز')
                            ->placeholder('-'),
                    ])->columnSpanFull()
                    ->columns(3),
                ViewPdf::make('document_path')
                    ->label('ملف المرفقات')
                    ->columnSpanFull(),
            ]);
    }
}
