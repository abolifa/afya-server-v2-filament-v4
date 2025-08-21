<?php

namespace App\Filament\Pages;

use App\Models\Product;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LowStockProductPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $title = 'الأصناف التي بلغت الحد الأدنى من المخزون';
    protected string $view = 'filament.pages.low-stock-product-page';

    public function table(Table $table): Table
    {
        return $table
            ->query($this->query())
            ->emptyStateIcon('fas-box-open')
            ->emptyStateHeading('لا توجد أصناف')
            ->columns([
                TextColumn::make('name')
                    ->label('المنتج')
                    ->searchable()
                    ->limit(28),
                TextColumn::make('type')
                    ->label('النوع')
                    ->alignCenter()
                    ->formatStateUsing(fn($state) => match ($state) {
                        'medicine' => 'دواء',
                        'equipment' => 'معدات',
                        'service' => 'خدمة',
                        'other' => 'أخرى',
                        default => $state
                    })
                    ->color(fn($state) => match ($state) {
                        'medicine' => 'info',
                        'equipment' => 'warning',
                        'service' => 'success',
                        'other' => 'danger',
                    })
                    ->badge(),


                TextColumn::make('stock')
                    ->label('المخزون')
                    ->alignCenter()
                    ->color(fn($state, $record) => $state <= 0
                        ? 'danger'
                        : ($state <= $record->alert_threshold ? 'warning' : 'success')),


            ])
            ->defaultSort('stock')
            ->paginated();
    }

    protected function query(): Builder
    {
        return Product::query()
            ->select('products.*')
            ->selectRaw("
                (
                    -- IN: invoices confirmed
                    COALESCE((
                        SELECT SUM(ii.quantity)
                        FROM invoice_items ii
                        JOIN invoices iv ON iv.id = ii.invoice_id
                        WHERE ii.product_id = products.id
                          AND iv.status = 'confirmed'
                    ),0)
                    +
                    -- IN: transfer IN (to any center)
                    COALESCE((
                        SELECT SUM(tii.quantity)
                        FROM transfer_invoice_items tii
                        JOIN transfer_invoices ti ON ti.id = tii.transfer_invoice_id
                        WHERE tii.product_id = products.id
                          AND ti.status='confirmed'
                    ),0)
                    -
                    -- OUT: patient orders
                    COALESCE((
                        SELECT SUM(oi.quantity)
                        FROM order_items oi
                        JOIN orders o ON o.id = oi.order_id
                        WHERE oi.product_id = products.id
                          AND o.status='confirmed'
                    ),0)
                    -
                    -- OUT: transfer OUT (from any center)
                    COALESCE((
                        SELECT SUM(tii2.quantity)
                        FROM transfer_invoice_items tii2
                        JOIN transfer_invoices ti2 ON ti2.id = tii2.transfer_invoice_id
                        WHERE tii2.product_id = products.id
                          AND ti2.status='confirmed'
                    ),0)
                ) AS stock
            ")
            ->havingRaw('stock <= alert_threshold')
            ->orderBy('stock');
    }
}
