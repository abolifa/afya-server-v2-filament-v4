<?php

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\LetterFactory;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Mpdf\Mpdf;
use Throwable;

class Letter extends Model
{
    /** @use HasFactory<LetterFactory> */
    use HasFactory;

    protected $fillable = [
        'center_id',
        'issue_number',
        'issue_date',
        'type',
        'to_center_id',
        'to_contact_id',
        'template_id',
        'follow_up_id',
        'subject',
        'to',
        'body',
        'tags',
        'cc',
        'attachments',
        'document_path',
        'qr_code',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'tags' => 'array',
        'cc' => 'array',
        'attachments' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (Letter $letter) {
            if (Auth::check() && Auth::user()->center_id) {
                $letter->center_id = Auth::user()->center_id;
            }
        });

        static::updating(function (Letter $letter) {
            if (Auth::check() && Auth::user()->center_id) {
                $letter->center_id = Auth::user()->center_id;
            }
        });
        static::addGlobalScope('center_scope', function ($builder) {
            $user = Auth::user();

            if ($user && $user->center_id) {
                $builder->where(function ($query) use ($user) {
                    $query->where('center_id', $user->center_id)
                        ->orWhere('to_center_id', $user->center_id);
                });
            }
        });

        static::created(function (Letter $letter) {
            $letter->generatePdf();
        });

        static::updated(function (Letter $letter) {
            $letter->generatePdf();
        });
    }


    public function generatePdf(): void
    {
        try {
            if ($this->document_path && Storage::disk('public')->exists($this->document_path)) {
                Storage::disk('public')->delete($this->document_path);
            }
            $fileName = "letters/letter_$this->id.pdf";
            $path = Storage::disk('public')->path($fileName);
            $html = View::make('print.letter', ['letter' => $this])->render();
            $config = [
                'mode' => 'utf-8',
                'format' => 'A4',
                'orientation' => 'P',
                'margin_top' => 50,
                'margin_right' => 20,
                'margin_bottom' => 20,
                'margin_left' => 20,
                'autoScriptToLang' => true,
                'autoLangToFont' => true,
                'dpi' => 150,

            ];
            $mpdf = new Mpdf($config);
            if ($this->template && $this->template->letterhead) {
                $letterheadPath = Storage::disk('public')->path($this->template->letterhead);
                if (file_exists($letterheadPath)) {
                    $mpdf->SetWatermarkImage($letterheadPath, 1.0, 'P', 'B');
                    $mpdf->showWatermarkImage = true;
                    $mpdf->watermarkImgBehind = true;
                }
            }
            $mpdf->SetHTMLHeader('
    <div style="position: fixed; left: 10px; top: 20px; font-size:12pt; text-align:center;">
        <div>' . Carbon::parse($this->issue_date)->format('d/m/Y') . '</div>
        <div style="margin-top:5px;">' . $this->issue_number . '</div>
    </div>
');

            $mpdf->SetHTMLFooter('
        <div style="text-align: center; font-size: 10pt;">
            صفحة {PAGENO} من {nb}
        </div>
    ');


            $mpdf->WriteHTML($html);
            Storage::disk('public')->put($fileName, $mpdf->Output('', 'S'));
            $this->updateQuietly(['document_path' => $fileName]);
        } catch (Throwable $e) {
            Log::error('PDF generation failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            Notification::make('Error')
                ->title('PDF generation failed')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }


    public function fromCenter(): BelongsTo
    {
        return $this->belongsTo(Center::class, 'center_id');
    }

    public function toCenter(): BelongsTo
    {
        return $this->belongsTo(Center::class, 'to_center_id');
    }

    public function toContact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'to_contact_id');
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    public function followUp(): BelongsTo
    {
        return $this->belongsTo(Letter::class, 'follow_up_id');
    }

    public function followUps(): HasMany
    {
        return $this->hasMany(Letter::class, 'follow_up_id');
    }
}
