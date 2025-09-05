<?php

namespace App\Filament\Archive\Widgets;

use App\Models\Contact;
use App\Models\Document;
use App\Models\Letter;
use App\Models\Template;
use Filament\Widgets\StatsOverviewWidget;

class SystemState extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $contactsCount = Contact::count();
        $documentsCount = Document::count();
        $lettersCount = Letter::count();
        $templatesCount = Template::count();
        return [
            StatsOverviewWidget\Stat::make('جهات الإتصال', $contactsCount)
                ->description('إجمالي جهات الاتصال المسجلة')
                ->url(fn() => route('filament.archive.resources.contacts.index'))
                ->color('success'),
            StatsOverviewWidget\Stat::make('المستندات', $documentsCount)
                ->description('إجمالي المستندات المحفوظة')
                ->url(fn() => route('filament.archive.resources.documents.index'))
                ->color('info'),
            StatsOverviewWidget\Stat::make('المراسلات', $lettersCount)
                ->description('إجمالي المراسلات المسجلة')
                ->url(fn() => route('filament.archive.resources.letters.index'))
                ->color('warning'),
            StatsOverviewWidget\Stat::make('القوالب', $templatesCount)
                ->description('إجمالي القوالب المتاحة')
                ->url(fn() => route('filament.archive.resources.templates.index'))
                ->color('danger'),
        ];
    }
}
