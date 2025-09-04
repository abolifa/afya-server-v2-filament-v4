<?php

namespace App\Services;

use iio\libmergepdf\Driver\Fpdi2Driver;
use iio\libmergepdf\Merger;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use RuntimeException;

class IncomingAttachmentService
{
    public function buildMergedPdf(array $attachments, int $ownerId, string $outputFolder = 'incomings'): ?string
    {
        $paths = $this->normalizeAttachmentPaths($attachments);
        if (empty($paths)) {
            Log::info('IncomingAttachmentService: no attachments', ['owner_id' => $ownerId, 'out' => $outputFolder]);
            return null;
        }

        $disk = 'public';
        $tmpPdfs = [];

        foreach ($paths as $relPath) {
            $ext = strtolower(pathinfo($relPath, PATHINFO_EXTENSION));
            if ($ext === 'pdf') {
                $tmpPdfs[] = $relPath;
            } else {
                $tmpPdfs[] = $this->imageToPdf($relPath);
            }
        }
        if (empty($tmpPdfs)) {
            return null;
        }
        $mergedPath = "$outputFolder/$ownerId/merged_" . Str::uuid() . ".pdf";
        $this->merge($tmpPdfs, $mergedPath);
        foreach ($tmpPdfs as $p) {
            if (!str_ends_with($p, '.pdf') || str_contains($p, '/_tmp/')) {
                Storage::disk($disk)->delete($p);
            }
        }
        return $mergedPath;
    }

    protected function normalizeAttachmentPaths(array $attachments): array
    {
        $paths = [];
        foreach ($attachments as $item) {
            if (is_string($item) && $item !== '') {
                $paths[] = $item;
            } elseif (is_array($item)) {
                $p = $item['path'] ?? $item['name'] ?? null;
                if (is_string($p) && $p !== '') $paths[] = $p;
            }
        }
        return array_values(array_filter($paths));
    }

    protected function imageToPdf(string $relPath): string
    {
        $disk = 'public';
        $abs = Storage::disk($disk)->path($relPath);
        if (!file_exists($abs)) {
            throw new RuntimeException("imageToPdf: file not found: $abs");
        }
        $tmpDir = 'incomings/_tmp';
        Storage::disk($disk)->makeDirectory($tmpDir);
        $out = $tmpDir . '/' . Str::uuid() . '.pdf';
        $mpdf = new Mpdf([
            'format' => 'A4',
            'margin_top' => 0,
            'margin_right' => 0,
            'margin_bottom' => 0,
            'margin_left' => 0,
            'tempDir' => Storage::disk($disk)->path($tmpDir),
        ]);
        $fileUrl = 'file://' . $abs;
        $html = <<<HTML
<!doctype html><html lang="ar" dir="rtl"><body style="margin:0;padding:0;">
<div style="width:210mm;height:297mm;display:flex;align-items:center;justify-content:center;">
  <img src="$fileUrl" style="max-width:200mm;max-height:287mm;object-fit:contain;" alt="">
</div>
</body></html>
HTML;

        $mpdf->WriteHTML($html);
        $mpdf->Output(Storage::disk($disk)->path($out), Destination::FILE);

        return $out;
    }

    protected function merge(array $pdfRelPaths, string $outRelPath): void
    {
        $disk = 'public';
        $merger = new Merger(new Fpdi2Driver());

        foreach ($pdfRelPaths as $rel) {
            $abs = Storage::disk($disk)->path($rel);
            if (file_exists($abs)) {
                $merger->addFile($abs);
            }
        }

        Storage::disk($disk)->makeDirectory(dirname($outRelPath));
        $binary = $merger->merge();
        Storage::disk($disk)->put($outRelPath, $binary, 'public');
    }
}
