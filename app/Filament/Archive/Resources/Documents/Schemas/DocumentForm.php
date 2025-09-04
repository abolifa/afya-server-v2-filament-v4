<?php

namespace App\Filament\Archive\Resources\Documents\Schemas;

use App\Helpers\CommonHelpers;
use App\Models\Document;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('issue_number')
                    ->label('الرقم الإشاري')
                    ->default(fn() => CommonHelpers::getIssueNumber(new Document()))
                    ->disabledOn('edit')
                    ->required(),
                Select::make('type')
                    ->label('نوع المستند')
                    ->options([
                        'letter' => 'خطاب',
                        'archive' => 'أرشيف',
                        'report' => 'تقرير',
                        'other' => 'أخرى',
                    ])
                    ->native(false)
                    ->required(),
                Select::make('from_contact_id')
                    ->label('من الجهة')
                    ->searchable()
                    ->preload()
                    ->relationship('fromContact', 'name'),
                Select::make('from_center_id')
                    ->label('من المركز')
                    ->searchable()
                    ->preload()
                    ->relationship('fromCenter', 'name'),
                DatePicker::make('issue_date')
                    ->label('تاريخ الإصدار')
                    ->default(Carbon::now())
                    ->required(),
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
                    ->directory('documents'),
            ]);
    }
}
