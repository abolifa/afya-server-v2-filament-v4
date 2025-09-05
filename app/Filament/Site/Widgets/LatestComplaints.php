<?php

namespace App\Filament\Site\Widgets;

use App\Models\Complaint;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestComplaints extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn(): Builder => Complaint::query()->latest())
            ->heading('أحدث الشكاوى')
            ->emptyStateHeading('لا توجد شكاوى حتى الآن')
            ->emptyStateDescription('لم يتم تقديم أي شكاوى من قبل المستخدمين.')
            ->emptyStateIcon('heroicon-o-inbox')
            ->paginated([10])
            ->columns([
                TextColumn::make('name')
                    ->label('الاسم')
                    ->placeholder('---')
                    ->sortable(),
                TextColumn::make('phone')
                    ->label('الهاتف')
                    ->placeholder('---')
                    ->sortable(),
                TextColumn::make('message')
                    ->label('الرسالة')
                    ->placeholder('---')
                    ->limit(50)
                    ->alignCenter()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('viewAll')
                    ->label('عرض الكل')
                    ->url(route('filament.site.resources.complaints.index'))
                    ->icon('heroicon-o-eye')
                    ->color('info'),
            ])
            ->recordActions([
                Action::make('view')
                    ->label('عرض')
                    ->url(fn(Complaint $record): string => route('filament.site.resources.complaints.view', $record))
                    ->icon('heroicon-o-eye')
                    ->color('success'),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
