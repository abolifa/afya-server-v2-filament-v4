@php
    $url = $getRecord()?->document_path
        ? asset('storage/' . $getRecord()->document_path) . '#zoom=90'
        : null;
@endphp

@if($url)
    <div class="w-full h-[300mm]">
        <iframe src="{{ $url }}" class="w-full h-full"></iframe>
    </div>
@else
    <p class="text-gray-500 text-center">لا يوجد ملف PDF للعرض</p>
@endif
