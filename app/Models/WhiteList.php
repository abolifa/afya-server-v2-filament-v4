<?php

namespace App\Models;

use Database\Factories\WhiteListFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhiteList extends Model
{
    /** @use HasFactory<WhiteListFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'can_see_activities',
        'can_see_all_stock',
        'can_select_all_centers',
    ];

    /**
     * علاقة مع المستخدم (كل WhiteList يخص User واحد).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
