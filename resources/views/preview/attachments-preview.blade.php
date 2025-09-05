@php use Illuminate\Support\Facades\Storage; @endphp
@if($getState() && is_array($getState()))
    <div class="space-y-4">
        @foreach($getState() as $file)
            @php
                $url = Storage::disk('public')->url($file);
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            @endphp

            <div class="border rounded p-2">
                @if(in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                    <img src="{{ $url }}" alt="Attachment">
                @elseif($ext === 'pdf')
                    <iframe src="{{ $url }}#zoom=80" style="height: 297mm;width: 100%;margin: 0 auto;"></iframe>
                @endif
            </div>
        @endforeach
    </div>
@else
    <p class="text-gray-500 text-sm">لا توجد مرفقات</p>
@endif
