<?php

namespace App\Filament\Pages;

use App\Filament\Forms\Components\BooleanField;
use App\Helpers\CommonHelpers;
use App\Models\Center;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Product;
use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Components\Fieldset;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class Stock extends Page implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms, HasPageShield;

    protected static string|null|BackedEnum $navigationIcon = 'fas-warehouse';
    protected static ?string $navigationLabel = 'المخزن';
    protected static ?string $title = 'المخزن';
    protected static string|null|UnitEnum $navigationGroup = 'إدارة المخزون';

    public bool $excludeZeros = false;
    public ?int $selectedCenterId = null;
    public bool $canChangeCenter = false;

    protected string $view = 'filament.pages.stock';

    public function mount(): void
    {
        $user = Auth::user();
        $whiteList = $user->whiteList;

        if ($whiteList && $whiteList->can_see_all_stock) {
            // يقدر يشوف كل المراكز
            $this->canChangeCenter = true;
            $this->selectedCenterId = $user->center_id; // يبدأ بمركزه لو عنده
        } else {
            // ما يقدر يشوف إلا مركزه
            if ($user->center_id) {
                $this->selectedCenterId = $user->center_id;
                $this->canChangeCenter = false;
            } else {
                abort(403, 'لا يمكن عرض المخزون');
            }
        }
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                $query = Product::query();

                if ($this->excludeZeros) {
                    $ids = $query->get()
                        ->filter(fn($product) => CommonHelpers::getStock($product->id, $this->selectedCenterId) > 0)
                        ->pluck('id');

                    if ($ids->isEmpty()) {
                        return $query->whereRaw('0 = 1');
                    }

                    $query->whereIn('id', $ids);
                }

                return $query;
            })
            ->heading(fn() => $this->selectedCenterId
                ? Center::find($this->selectedCenterId)?->name ?? 'لا يوجد مركز'
                : 'كل المراكز')
            ->columns([
                TextColumn::make('name')
                    ->label('المنتج')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('total_invoice')
                    ->label('مجموع الوارد')
                    ->alignCenter()
                    ->prefix(fn($state) => $state === 0 ? '' : '+')
                    ->color('success')
                    ->getStateUsing(fn($record) => CommonHelpers::getStockModel($record->id, Invoice::class, $this->selectedCenterId)),
                TextColumn::make('total_order')
                    ->label('مجموع الصادر')
                    ->alignCenter()
                    ->color(fn($state) => $state === 0 ? 'gray' : 'danger')
                    ->prefix(fn($state) => $state === 0 ? '' : '-')
                    ->getStateUsing(fn($record) => CommonHelpers::getStockModel($record->id, Order::class, $this->selectedCenterId)),
                TextColumn::make('transfer_in')
                    ->label('تحويل وارد')
                    ->alignCenter()
                    ->prefix(fn($state) => $state === 0 ? '' : '+')
                    ->color('success')
                    ->getStateUsing(fn($record) => $this->selectedCenterId
                        ? CommonHelpers::getTransferStock($record->id, $this->selectedCenterId)['in']
                        : 0),
                TextColumn::make('transfer_out')
                    ->label('تحويل صادر')
                    ->alignCenter()
                    ->prefix(fn($state) => $state === 0 ? '' : '-')
                    ->getStateUsing(fn($record) => $this->selectedCenterId
                        ? CommonHelpers::getTransferStock($record->id, $this->selectedCenterId)['out']
                        : 0),
                TextColumn::make('real_stock')
                    ->label('المخزون الفعلي *قطعة')
                    ->alignCenter()
                    ->getStateUsing(fn($record) => CommonHelpers::getStock($record->id, $this->selectedCenterId))
                    ->color(fn($state, $record) => $state === 0 ? 'danger' : ($state <= $record->alert_threshold ? 'warning' : 'success')),
            ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Fieldset::make('خيارات العرض')->schema([
                Select::make('selectedCenterId')
                    ->label('المركز')
                    ->placeholder('كل المراكز')
                    ->options(Center::all()->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->disabled(fn() => !$this->canChangeCenter)
                    ->reactive(),
                // رجّعت excludeZeros زي ما كان
                BooleanField::make('excludeZeros')
                    ->label('استثناء الكميات صفر')
                    ->default(false)
                    ->reactive(),
            ])->columns(),
        ];
    }
}
