<?php

namespace App\Filament\Archive\Resources\Letters\Schemas;

use App\Helpers\CommonHelpers;
use App\Models\Center;
use App\Models\Contact;
use App\Models\Letter;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class LetterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make('البيانات الأساسية')
                    ->schema([
                        TextInput::make('issue_number')
                            ->label('الرقم الإشاري')
                            ->default(fn() => CommonHelpers::getIssueNumber(new Letter()))
                            ->required(),
                        DatePicker::make('issue_date')
                            ->label('تاريخ الإصدار')
                            ->default(Carbon::now())
                            ->required(),
                        Select::make('type')
                            ->label('نوع الخطاب')
                            ->options([
                                'internal' => 'داخلي',
                                'external' => 'خارجي',
                            ])
                            ->default('internal')
                            ->native(false)
                            ->reactive()
                            ->required(),
                        Select::make('follow_up_id')
                            ->label('تابع للخطاب رقم')
                            ->searchable()
                            ->preload()
                            ->relationship('followUp', 'issue_number'),
                        Select::make('to_center_id')
                            ->label('إلى المركز')
                            ->searchable()
                            ->preload()
                            ->required(fn(callable $get) => $get('type') === 'internal')
                            ->disabled(fn(callable $get) => $get('type') === 'external')
                            ->reactive()
                            ->live(onBlur: true)
                            ->afterStateUpdated(
                                function ($state, Set $set) {
                                    $center = Center::find($state);
                                    if ($center) {
                                        $set('to', $center->name);
                                    } else {
                                        $set('to', null);
                                    }
                                }
                            )
                            ->relationship('toCenter', 'name'),
                        Select::make('to_contact_id')
                            ->label('إلى جهة الاتصال')
                            ->searchable()
                            ->preload()
                            ->required(fn(callable $get) => $get('type') === 'external')
                            ->disabled(fn(callable $get) => $get('type') === 'internal')
                            ->reactive()
                            ->live(onBlur: true)
                            ->afterStateUpdated(
                                function ($state, Set $set) {
                                    $contact = Contact::find($state);
                                    if ($contact) {
                                        $set('to', $contact->name);
                                    } else {
                                        $set('to', null);
                                    }
                                }
                            )
                            ->relationship('toContact', 'name'),
                    ])->columnSpanFull()
                    ->columns(),

                TextInput::make('qr_code')
                    ->label('رمز QR')
                    ->required()
                    ->suffixAction(
                        Action::make('generateQRCode')
                            ->label('توليد رمز QR')
                            ->icon(Heroicon::QrCode)
                            ->tooltip('توليد رمز QR للرسالة')
                            ->action(function (Get $get, Set $set) {
                                $qr = CommonHelpers::buildOutgoingQrPayload(
                                    $get('issue_number')
                                );
                                $set('qr_code', $qr);
                            })
                    ),
                Select::make('template_id')
                    ->label('القالب')
                    ->searchable()
                    ->preload()
                    ->relationship('template', 'name'),
                TextInput::make('to')
                    ->label('إلى')
                    ->required(),
                TextInput::make('subject')
                    ->label('الموضوع')
                    ->required(),
                RichEditor::make('body')
                    ->label('نص الخطاب')
                    ->columnSpanFull(),
                TagsInput::make('cc')
                    ->placeholder('أضف بريد إلكتروني')
                    ->columnSpanFull()
                    ->label('نسخة إلى'),
                FileUpload::make('attachments')
                    ->label('المرفقات')
                    ->multiple()
                    ->acceptedFileTypes([
                        'image/*',
                        'application/pdf',
                    ])
                    ->disk('public')
                    ->visibility('public')
                    ->columnSpanFull()
                    ->directory('letters'),
            ]);
    }
}
