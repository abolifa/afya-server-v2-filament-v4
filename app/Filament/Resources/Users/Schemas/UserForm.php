<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Filament\Forms\Components\BooleanField;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Role;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('الإسم')
                    ->required(),
                TextInput::make('email')
                    ->label('البريد الإلكتروني')
                    ->email()
                    ->required(),
                TextInput::make('phone')
                    ->label('رقم الهاتف')
                    ->tel(),
                TextInput::make('password')
                    ->label('كلمة المرور')
                    ->required(fn($operation) => $operation === 'create')
                    ->disabled(fn($operation) => $operation === 'edit')
                    ->password(),
                Select::make('center_id')
                    ->label('المركز')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->relationship('center', 'name'),
                Select::make('roles')
                    ->label('الصلاحيات')
                    ->multiple()
                    ->relationship('roles', 'name')
                    ->searchable()
                    ->preload()
                    ->options(
                        Role::query()
                            ->where('name', '!=', config('filament-shield.super_admin.name', 'super_admin'))
                            ->pluck('name', 'id')
                    ),

                Section::make('صلاحيات المستخدم')->columns(4)->columnSpanFull()
                    ->schema([
                        BooleanField::make('active'),
                        BooleanField::make('doctor')
                            ->label('طبيب')
                            ->helperText('يتيح للمستخدم الوصول إلى خصائص الطبيب')
                            ->default(false),
                        BooleanField::make('see_all_stock')
                            ->label('كل المخازن')
                            ->helperText('يتيح للمستخدم رؤية كل المخازن وليس مخزن المركز فقط')
                            ->default(false),
                        BooleanField::make('see_all_center')
                            ->label('كل المراكز')
                            ->helperText('يتيح للمستخدم رؤية بيانات كل المراكز')
                            ->default(false),
                        BooleanField::make('see_activities')
                            ->label('مشاهدة النشاطات')
                            ->helperText('يتيح للمستخدم مشاهدة سجل النشاطات')
                            ->default(false),
                        BooleanField::make('access_patient')
                            ->label('نظام عافية')
                            ->helperText('يتيح للمستخدم الوصول لوحة تحكم عافية')
                            ->default(false),
                        BooleanField::make('access_site')
                            ->label('الموقع الإلكتروني')
                            ->helperText('يتيح للمستخدم الوصول إلى لوحة تحكم الموقع الإلكتروني')
                            ->default(false),
                        BooleanField::make('access_archive')
                            ->label('الأرشيف')
                            ->helperText('يتيح للمستخدم الوصول إلى لوحة تحكم الأرشيف')
                            ->default(false),
                    ]),
            ]);
    }
}
