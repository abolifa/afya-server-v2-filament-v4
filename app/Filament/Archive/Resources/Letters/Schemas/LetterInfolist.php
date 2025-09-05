<?php

namespace App\Filament\Archive\Resources\Letters\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LetterInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make('المعلومات الأساسية')
                    ->schema([
                        TextEntry::make('issue_number')
                            ->label('الرقم الإشاري'),
                        TextEntry::make('issue_date')
                            ->label('تاريخ الإصدار')
                            ->date('d/m/Y'),
                        TextEntry::make('type')
                            ->label('نوع الخطاب')
                            ->formatStateUsing(fn($state) => match ($state) {
                                'internal' => 'داخلي',
                                'external' => 'خارجي',
                                default => $state,
                            }),
                        TextEntry::make('template.name')
                            ->label('القالب'),
                        TextEntry::make('followUp.issue_number')
                            ->label('تابع للخطاب رقم')
                            ->placeholder('-'),
                        TextEntry::make('subject')
                            ->label('الموضوع'),
                        TextEntry::make('to')
                            ->label('إلى'),
                    ])->columns(4)
                    ->columnSpanFull(),

                Section::make('الرسالة')
                    ->schema([
                        ViewEntry::make('body')
                            ->label('نص الرسالة')
                            ->view('preview.letter-preview')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                Section::make('المرفقات')
                    ->schema([
                        ViewEntry::make('attachments')
                            ->label('المرفقات')
                            ->view('preview.attachments-preview'),
                    ])->columnSpanFull()
                    ->columns(1)
                    ->hidden(fn($record) => empty($record->attachments)),
            ]);
    }
}
