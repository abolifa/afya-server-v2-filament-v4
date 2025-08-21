@php
    use Carbon\Carbon;
@endphp

<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }

    th, td {
        padding: 8px;
        text-align: center;
        border-bottom: 1px solid #ddd;
    }
</style>

<x-filament-panels::page>
    {{ $this->infolist }}
    @livewire(\App\Livewire\PatientVitalsChart::class, ['patientId' => $this->patient->id])
    <x-filament::section heading="المواعيد">
        @if ($this->patient->appointments->isEmpty())
            <div>لا توجد مواعيد مسجلة لهذا المريض.</div>
        @else
            <table>
                <thead>
                <tr>
                    <th>
                        المركز
                    </th>
                    <th>
                        الطبيب
                    </th>
                    <th>
                        التاريخ
                    </th>
                    <th>
                        الساعة
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach ($this->patient->appointments as $appointment)
                    <tr>
                        <td>
                            {{ $appointment->center->name }}
                        </td>
                        <td>
                            {{ $appointment->doctor->name }}
                        </td>
                        <td class="px-6 py-4">
                            {{ Carbon::parse($appointment->date)->format('d/m/y') }}
                        </td>
                        <td class="px-6 py-4">
                            {{ Carbon::parse($appointment->time)->format('h:i A') }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </x-filament::section>
    <x-filament::section heading="الطلبات">
        @if ($this->patient->orders->isEmpty())
            <div>لا توجد طلبات مسجلة لهذا المريض.</div>
        @else
            <table>
                <thead>
                <tr>
                    <th>
                        المركز
                    </th>
                    <th>
                        حالة الطلب
                    </th>
                    <th>
                        الأصناف
                    </th>
                    <th>
                        التاريخ
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach ($this->patient->orders as $order)
                    <tr>
                        <td>
                            {{ $order->center->name }}
                        </td>
                        <td>
                            {{ $order->status }}
                        </td>
                        <td class="px-6 py-4">
                            {{ ($order->items ?? collect())->sum('quantity') }}
                        </td>
                        <td class="px-6 py-4">
                            {{ Carbon::parse($order->created_at)->format('d/m/y') }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </x-filament::section>
</x-filament-panels::page>
