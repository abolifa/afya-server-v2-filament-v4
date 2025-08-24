<?php

namespace App\Models;

use App\Traits\HasBlamesUsers;
use App\Traits\HasCenterScope;
use Database\Factories\InvoiceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    /** @use HasFactory<InvoiceFactory> */
    use HasFactory, HasCenterScope, SoftDeletes, HasBlamesUsers;

    protected $fillable = ['center_id', 'supplier_id', 'status'];

    public function center(): BelongsTo
    {
        return $this->belongsTo(Center::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
