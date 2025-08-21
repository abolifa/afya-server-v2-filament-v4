<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    protected $fillable = ['type', 'name', 'image', 'expiry_date',
        'description', 'usage', 'alert_threshold'];

    protected $appends = ['stock'];

//    public function getStockAttribute(): int
//    {
//        return CommonHelpers::getStock($this->id);
//    }

    public function transferItems(): HasMany
    {
        return $this->hasMany(TransferInvoiceItem::class);
    }

    public function stockMovementItems(): HasMany
    {
        return $this->hasMany(StockMovementItem::class);
    }

    public function prescriptionItems(): HasMany
    {
        return $this->hasMany(PrescriptionItem::class);
    }

    public function getStockAttribute(): int
    {
        $invoices = $this->invoiceItems()
            ->whereHas('invoice', fn($q) => $q->where('status', 'confirmed'))
            ->sum('quantity');

        $orders = $this->orderItems()
            ->whereHas('order', fn($q) => $q->where('status', 'confirmed'))
            ->sum('quantity');

        return $invoices - $orders;
    }

    public function invoiceItems(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

}
