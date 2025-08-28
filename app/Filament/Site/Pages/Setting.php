<?php

namespace App\Filament\Site\Pages;

use App\Models\Setting as SettingModel;
use BackedEnum;
use Filament\Actions;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class Setting extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|null|BackedEnum $navigationIcon = 'gmdi-blinds-tt';
    protected static ?string $navigationLabel = 'بيانات الهيئة';
    protected static ?string $title = 'بيانات الهيئة';
    public ?array $data = [];
    protected string $view = 'filament.site.pages.setting';
    protected ?SettingModel $record = null;

    public function mount(): void
    {
        $this->fillFormFromDb();
    }

    protected function fillFormFromDb(): void
    {
        $record = $this->getSettingsRecord();
        $this->data = $record->toArray();
        $this->form->fill($this->data);
    }

    protected function getSettingsRecord(): SettingModel
    {
        return SettingModel::query()->first() ?? SettingModel::create([]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->schema([
                Section::make('عن الهيئة')->schema([
                    TextInput::make('about_title')->label('العنوان'),
                    RichEditor::make('about_content')->label('النص'),
                ])->columnSpanFull(),
                Section::make('سياسة الخصوصية')->schema([
                    TextInput::make('privacy_policy_title')->label('العنوان'),
                    RichEditor::make('privacy_policy_content')->label('النص'),
                ])->columnSpanFull(),
                Section::make('شروط الخدمة')->schema([
                    TextInput::make('terms_of_service_title')->label('العنوان'),
                    RichEditor::make('terms_of_service_content')->label('النص'),
                ])->columnSpanFull(),
                Section::make('الأسئلة الشائعة')->schema([
                    TextInput::make('faq_title')->label('العنوان'),
                    RichEditor::make('faq_content')->label('النص'),
                    Repeater::make('faq')
                        ->label('الأسئلة الشائعة')
                        ->schema([
                            TextInput::make('question')->label('السؤال'),
                            TextInput::make('answer')->label('الإجابة'),
                        ])
                        ->columns()
                        ->defaultItems(1),
                ])->columnSpanFull(),
                Section::make('تواصل معنا')->schema([
                    Repeater::make('contact')
                        ->label('طرق الاتصال')
                        ->schema([
                            ToggleButtons::make('type')
                                ->label('نوع الاتصال')
                                ->options([
                                    'email' => 'البريد الإلكتروني',
                                    'phone' => 'رقم الهاتف',
                                ])
                                ->inline()
                                ->grouped()
                                ->columnSpanFull(),
                            TextInput::make('name')->label('الاسم'),
                            TextInput::make('value')->label('رقم الهاتف أو البريد الإلكتروني'),
                            TextInput::make('time_period')->label('وقت الرد')->nullable(),
                            TextInput::make('zone')->label('المنطقة')->nullable(),
                        ])
                        ->columns()
                        ->defaultItems(1),
                ])->columnSpanFull(),
            ]);
    }

    /** Persist form to DB */
    public function save(): void
    {
        if (method_exists($this->form, 'validate')) {
            $this->form->validate();
        }
        $data = $this->form->getState();

        $record = $this->getSettingsRecord();
        $record->fill($data);
        $record->save();

        Notification::make()
            ->title('تم حفظ الإعدادات بنجاح')
            ->success()
            ->send();
    }

    /** Header buttons */
    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('save')
                ->label('حفظ')
                ->color('primary')
                ->keyBindings(['mod+s'])
                ->action('save'),
            Actions\Action::make('reset')
                ->label('إلغاء التغييرات')
                ->color('gray')
                ->action(fn() => $this->fillFormFromDb()),
        ];
    }
}
