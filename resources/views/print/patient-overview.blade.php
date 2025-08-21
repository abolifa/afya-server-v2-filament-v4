@php use Carbon\Carbon; @endphp
    <!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>كشف مريض</title>

    <style>
        body {
            font-family: "tajawal", sans-serif;
        }

        .page-content {
            width: 100%;
            height: 100%;
            padding: 20px 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        tr {
            padding: 5px;
        }

        td {
            background-color: transparent;
            border: 1px solid #888888;
            padding: 5px;
            vertical-align: center;

        }

        /* New styles for the vitals table */
        .vitals-table td,
        .vitals-table th {
            text-align: center;
            border: 1px solid #888888;
            padding: 8px;
            font-size: 14px;
        }

        .vitals-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        h1 {
            font-size: 22px;
        }
    </style>
</head>
<body>
<div class="page-content">
    <div class="brand-logo">
        <table>
            <tr>
                <td style="width: 60px; border: none; padding: 0;">
                    <img src="{{ public_path('logo.png') }}" alt="Logo" style="width: 60px; height: auto;">
                </td>
                <td style="border: none;">
                    <h1>نظام عافية الصحي</h1>
                    <p style="font-size: 18px; line-height: 1.5;">كشف مريض</p>
                </td>
                <td style="text-align: left; border: none; padding: 0;">
                    <p>{{ Carbon::now()->format('d/m/Y - h:i A') }}</p>
                </td>
            </tr>
        </table>


        <table style="margin-top: 20px;">
            <tr>
                <td>
                    <strong>اسم المريض</strong>
                    <p>{{ $patient->name }}</p>
                </td>
                <td>
                    <strong>الرقم الوطني</strong>
                    <p>{{ $patient->national_id }}</p>
                </td>
                <td>
                    <strong>رقم الملف</strong>
                    <p>{{ $patient->file_number }}</p>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>المركز</strong>
                    <p>{{ $patient->center->name }}</p>
                </td>
                <td>
                    <strong>رقم قيد العائلة</strong>
                    <p>{{ $patient->family_issue ?? "--" }}</p>
                </td>
                <td>
                    <strong>رقم الهاتف</strong>
                    <p>{{ $patient->phone ?? "--" }}</p>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>جنس المريض</strong>
                    <p>{{ $patient->gender ?? "--" }}</p>
                </td>
                <td>
                    <strong>تاريخ الميلاد</strong>
                    <p>{{ $patient->dob ? Carbon::parse($patient->dob)->format('d/m/Y') : "--" }}</p>
                </td>
                <td>
                    <strong>فصيلة الدم</strong>
                    <p>{{ $patient->blood_group ?? "--" }}</p>
                </td>
            </tr>

            <tr>
                <td>
                    <strong>الجهاز</strong>
                    <p>{{ $patient->device->name ?? "--" }}</p>
                </td>
                <td>
                    <strong>البريد الإلكتروني</strong>
                    <p>{{ $patient->email ?? "--" }}</p>
                </td>
                <td>
                    <strong>مستوفي البيانات</strong>
                    <p>{{ $patient->verified ? "نعم" : "لا" }}</p>
                </td>
            </tr>
        </table>

        <!-- Vitals Table -->
        @if($patient->vitals && $patient->vitals->count() > 0)
            <h2 style="margin-top: 20px;">القياسات الحيوية</h2>
            <table class="vitals-table">
                <thead>
                <tr>
                    <th>تاريخ القياس</th>
                    <th>الوزن</th>
                    <th>ضغط الدم</th>
                    <th>معدل نبضات القلب</th>
                    <th>درجة الحرارة</th>
                    <th>مستوى الأكسجين</th>
                    <th>مستوى السكر</th>
                </tr>
                </thead>
                <tbody>
                @foreach($patient->vitals as $vital)
                    <tr>
                        <td>{{ Carbon::parse($vital->recorded_at)->format('d/m/Y') }}</td>
                        <td>{{ round($vital->weight) ?? "--" }} كجم</td>
                        <td>{{ ($vital->systolic ?? "--") . "/" . ($vital->diastolic ?? "--") }}</td>
                        <td>{{ round($vital->heart_rate) ?? "--" }}</td>
                        <td>{{ round($vital->temperature) ?? "--" }}</td>
                        <td>{{ round($vital->oxygen_saturation) ?? "--" }}</td>
                        <td>{{ round($vital->sugar_level) ?? "--" }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif

        @if($patient->appointments && $patient->appointments->count() > 0)
            <h2 style="margin-top: 20px;">المواعيد</h2>
            <table class="vitals-table">
                <thead>
                <tr>
                    <th>المركز</th>
                    <th>التاريخ</th>
                    <th>الساعة</th>
                    <th>حالة الموعد</th>
                    <th>حضور</th>
                    <th>وقت البداية</th>
                    <th>وقت الانتهاء</th>
                </tr>
                </thead>
                <tbody>
                @foreach($patient->appointments as $appointment)
                    <tr>
                        <td>{{ $appointment->center->name }}</td>
                        <td>{{ Carbon::parse($appointment->date)->format('d/m/Y') }}</td>
                        <td>{{ Carbon::parse($appointment->time)->format('h:i A') }}</td>
                        <td>{{ $appointment->status }}</td>
                        <td>{{ $appointment->intended ? "نعم" : "لا" }}</td>
                        <td>{{ $appointment->start_time ? Carbon::parse($appointment->start_time)->format('h:i A') : "--" }}</td>
                        <td>{{ $appointment->end_time ? Carbon::parse($appointment->end_time)->format('h:i A') : "--" }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif


        @if($patient->orders && $patient->orders->count() > 0)
            <h2 style="margin-top: 20px;">الطلبات</h2>
            <table class="vitals-table">
                <thead>
                <tr>
                    <th>رقم الطلب</th>
                    <th>المركز</th>
                    <th>حالة الطلب</th>
                    <th>عدد الأصناف</th>
                    <th>تاريخ الطلب</th>
                </tr>
                </thead>
                <tbody>
                @foreach($patient->orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->center->name }}</td>
                        <td>{{ $order->status }}</td>
                        <td>{{ $order->items->count() > 0 ?  ($order->items ?? collect())->sum('quantity') : "--"}}</td>
                        <td>{{ Carbon::parse($order->created_at)->format('h:i A') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
</body>
</html>
