<?php

namespace App\Models;

use Database\Factories\DocumentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    /** @use HasFactory<DocumentFactory> */
    use HasFactory;

    protected $fillable = [
        'issue_number',
        'type',
        'from_contact_id',
        'from_center_id',
        'issue_date',
        'attachments',
        'document_path',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'attachments' => 'array',
    ];

    protected static function booted(): void
    {
        static::deleting(function (Document $doc) {
            if ($doc->document_path) {
                Storage::disk('public')->delete($doc->document_path);
            }
        });
    }

    public function fromContact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'from_contact_id');
    }

    public function fromCenter(): BelongsTo
    {
        return $this->belongsTo(Center::class, 'from_center_id');
    }
}
