@php
    use Illuminate\Support\Facades\Storage;
    $path = $getState();
    $url = $path ? Storage::disk('public')->url($path) : null;
@endphp

<style>
    iframe {
        height: 250mm;
        border: none;
        border-radius: 8px;
        width: 100%;
    }
</style>

<div style="width: 100%;">
    @if ($url)
        <iframe
            src="{{ $url }}#zoom=75%"
            class="w-full"

        ></iframe>
    @else
        <div>
            <p class="text-gray-500 text-center py-8">لا يوجد ملف PDF لعرضه</p>
        </div>
    @endif
</div>
