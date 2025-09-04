<?php

namespace App\Models;

use Database\Factories\ContactFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contact extends Model
{
    /** @use HasFactory<ContactFactory> */
    use HasFactory;

    protected $fillable = [
        'type',
        'name',
        'email',
        'phone',
    ];

    public function letters(): HasMany
    {
        return $this->hasMany(Letter::class, 'to_contact_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'from_contact_id');
    }
}
