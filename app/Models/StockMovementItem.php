<?php

namespace App\Models;

use Database\Factories\StockMovementItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovementItem extends Model
{
    /** @use HasFactory<StockMovementItemFactory> */
    use HasFactory;

    protected $fillable = ['stock_movement_id', 'product_id', 'quantity', 'unit_id'];

    public function stockMovement(): BelongsTo
    {
        return $this->belongsTo(StockMovement::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}
