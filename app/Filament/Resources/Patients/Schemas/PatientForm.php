<?php

namespace App\Filament\Resources\Patients\Schemas;

use App\Filament\Forms\Components\BooleanField;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class PatientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make([
                    FileUpload::make('image')
                        ->columnSpanFull()
                        ->alignCenter()
                        ->avatar()
                        ->label('الصورة الشخصية')
                        ->visibility('public')
                        ->disk('public')
                        ->image(),
                    TextInput::make('name')
                        ->label('الإسم')
                        ->required(),

                    TextInput::make('national_id')
                        ->label('الرقم الوطني')
                        ->required()
                        ->live(onBlur: true) // only updates state on blur
                        ->rule('regex:/^(?:11|12|21|22)\d{10}$/')
                        ->unique(column: 'national_id', ignoreRecord: true)
                        ->validationMessages([
                            'regex' => 'صيغة الرقم الوطني غير صحيحة',
                            'unique' => 'هذا الرقم الوطني مسجل بالفعل',
                        ])
                        ->afterStateUpdated(function (Set $set, ?string $state) {
                            if (blank($state) || !preg_match('/^(?:11|12|21|22)\d{10}$/', $state)) {
                                $set('gender', null);
                                return;
                            }

                            $first = $state[0];
                            $set('gender', $first === '1' ? 'male' : ($first === '2' ? 'female' : null));
                        }),
                    TextInput::make('file_number')
                        ->label('رقم الملف الطبي')
                        ->live(onBlur: true)
                        ->unique(column: 'file_number', ignoreRecord: true)
                        ->required()
                        ->suffixAction(
                            Action::make('generate')
                                ->label('توليد رقم عشوائي')
                                ->icon('heroicon-o-sparkles')
                                ->color('success')
                                ->requiresConfirmation()
                                ->tooltip('توليد رقم عشوائي للملف الطبي')
                                ->action(function (Set $set) {
                                    $randomNumber = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                                    $set('file_number', $randomNumber);
                                }
                                ))
                        ->validationMessages([
                            'unique' => 'هذا الرقم الطبي مسجل بالفعل',
                        ]),
                    TextInput::make('family_issue_number')
                        ->label('رقم قيد العائلة'),
                    TextInput::make('phone')
                        ->label('رقم الهاتف')
                        ->tel()
                        ->required(),
                    TextInput::make('password')
                        ->label('كلمة المرور')
                        ->password()
                        ->required(),
                    Select::make('center_id')
                        ->label('المركز')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->default(auth()->user()->center_id ?? null)
                        ->relationship('center', 'name'),
                    Select::make('device_id')
                        ->searchable()
                        ->label('الجهاز')
                        ->preload()
                        ->relationship('device', 'name'),
                ])->columns()
                    ->columnSpanFull(),
                Section::make([
                    TextInput::make('email')
                        ->label('البريد الإلكتروني')
                        ->email(),
                    Select::make('gender')
                        ->label('الجنس')
                        ->options(['male' => 'ذكر', 'female' => 'أنثى']),
                    DatePicker::make('dob')
                        ->label('تاريخ الميلاد'),
                    Select::make('blood_group')
                        ->label('فصيلة الدم')
                        ->options([
                            'A+' => 'A+',
                            'A-' => 'A-',
                            'B+' => 'B+',
                            'B-' => 'B-',
                            'AB+' => 'AB+',
                            'AB-' => 'AB-',
                            'O+' => 'O+',
                            'O-' => 'O-',
                        ]),
                    BooleanField::make('verified')
                        ->label('مستوفي البيانات')
                        ->default(false)
                        ->required(),
                ])->columns()
                    ->columnSpanFull(),
            ]);
    }
}
