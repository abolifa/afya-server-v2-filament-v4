@php
    use Carbon\Carbon;
@endphp

    <!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $letter->issue_number }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
        }

        h1 {
            line-height: 1.5;
            font-size: 17pt;
            font-weight: bold;
        }

        p {
            margin: 0;
            padding: 0;
            line-height: 1.5;
            font-size: 14pt;
        }
    </style>
</head>
<body>


<table style="width: 100%;">
    <tr>
        <td style="text-align: right; vertical-align: center;">
            <h1>السادة / {{ $letter->to }}</h1>
            <br/>
            <h1>الموضوع / {{ $letter->subject }}</h1>
        </td>
        <td style="text-align: left; vertical-align: center; width: 120px;">
            @if($letter->qr_code)
                <barcode code="{{ $letter->qr_code }}" type="QR" size="1.2" error="M"></barcode>
            @endif
        </td>
    </tr>
</table>
<p>{{ $letter->template?->greetings }}</p>
<br/>
<div style="margin: 0 10px; text-align: justify;">{!! $letter->body !!}</div>
<br/>
<p style="text-align: center; font-weight: bold">{{ $letter->template?->closing }}</p>
<br/>
<div style="width: 100%; page-break-inside: avoid; direction: ltr;">
    <div style="width: 300px; text-align: center;">

        @if ($letter->template?->show_role)
            <p style="margin:0; padding:5px; font-weight: bold;">
                {{ $letter->template?->role }}
            </p>
        @endif

        @if ($letter->template?->show_commissioner)
            <p style="margin:0; padding:5px; font-weight: bold;">
                {{ $letter->template?->commissioner }}
            </p>
        @endif

        <table style="width: 100%; page-break-inside: avoid;">
            <tr>
                <td style="text-align: center; vertical-align: top;">
                    @if ($letter->template?->show_stamp)
                        <img src="{{ public_path('storage/' . $letter->template?->stamp) }}"
                             alt="الختم" height="150">
                    @endif
                </td>
                <td style="text-align: center; vertical-align: top;">
                    @if ($letter->template?->show_signature)
                        <img src="{{ public_path('storage/' . $letter->template?->signature) }}"
                             alt="التوقيع" height="150">
                    @endif
                </td>
            </tr>
        </table>
    </div>
</div>
</body>
</html>
