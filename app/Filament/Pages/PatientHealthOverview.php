<?php

namespace App\Filament\Pages;

use App\Models\Patient;
use Filament\Actions\Action;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;

class PatientHealthOverview extends Page implements HasInfolists
{
    use InteractsWithInfolists;


    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $slug = 'patients/{patient}/overview';
    protected static ?string $title = 'كشف مريض';
    public Patient $patient;
    protected string $view = 'filament.pages.patient-health-overview';

    public function mount(Patient $patient): void
    {
        $this->patient = $patient;
    }

    public function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('رجوع')
                ->icon('heroicon-o-arrow-right')
                ->color('slate')
                ->url(url()->previous())
                ->requiresConfirmation(false),
            Action::make('print')
                ->label('طباعة')
                ->icon('heroicon-o-printer')
                ->color('primary')
                ->url(route('print.patient-overview', ['patient' => $this->patient->id]))
                ->openUrlInNewTab()
                ->requiresConfirmation(false),

        ];
    }

    public function infolist(Schema $infolist): Schema
    {
        return $infolist
            ->record($this->patient)
            ->schema([
                Section::make('بيانات المريض')
                    ->icon('fas-user')
                    ->headerActions([
//                        EditAction::make(),
                    ])
                    ->schema([
                        ImageEntry::make('image')
                            ->columnSpanFull()
                            ->hiddenLabel()
                            ->alignCenter()
                            ->imageSize(130)
                            ->extraAttributes([
                                'style' => 'margin-bottom: 30px;',
                            ]),
                        TextEntry::make('name')
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::SemiBold)
                            ->label('اسم المريض'),
                        TextEntry::make('national_id')
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::SemiBold)
                            ->label('الرقم الوطني'),
                        TextEntry::make('file_number')
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::SemiBold)
                            ->label('رقم الملف'),
                        TextEntry::make('phone')
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::SemiBold)
                            ->label('رقم الهاتف'),
                        TextEntry::make('family_issue_number')
                            ->placeholder('لا يوجد')
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::SemiBold)
                            ->label('رقم قيد العائلة'),
                        TextEntry::make('email')
                            ->placeholder('لا يوجد')
                            ->limit(25)
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::SemiBold)
                            ->tooltip(fn($state) => $state ?? 'لا يوجد')
                            ->label('البريد الإلكتروني'),
                        TextEntry::make('gender')
                            ->placeholder('لا يوجد')
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::SemiBold)
                            ->label('الجنس'),
                        TextEntry::make('dob')
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::SemiBold)
                            ->label('تاريخ الميلاد')
                            ->placeholder('لا يوجد')
                            ->date('d/m/Y'),
                        TextEntry::make('blood_group')
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::SemiBold)
                            ->placeholder('لا يوجد')
                            ->label('فصيلة الدم'),
                        TextEntry::make('center.name')
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::SemiBold)
                            ->label('المركز الصحي'),
                        TextEntry::make('device.name')
                            ->placeholder('لا يوجد')
                            ->size(TextSize::Medium)
                            ->weight(FontWeight::SemiBold)
                            ->label('الجهاز'),
                        IconEntry::make('verified')
                            ->label('مستوفي البيانات')
                            ->boolean(),
                    ])->columns([
                        'sm' => 1,
                        'md' => 2,
                        'lg' => 3,
                        'xl' => 4,
                    ]),
            ]);
    }

}
