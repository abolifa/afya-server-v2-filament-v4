<x-dynamic-component
    :component="$getEntryWrapperView()"
    :entry="$entry"
>
    <div {{ $getExtraAttributeBag() }}>
        @php
            $items = $getState() ?? [];
        @endphp

        <style>
            .custom-table {
                width: 100%;
                border-collapse: collapse;
                font-size: 14px;
            }

            .custom-table th,
            .custom-table td {
                border: 1px solid #ddd;
                padding: 8px 12px;
                text-align: center;
            }

            .custom-table thead {
                background-color: rgba(0, 0, 0, 0.05);
                font-weight: bold;
            }

            .custom-table tbody tr:nth-child(odd) {
                background-color: rgba(0, 0, 0, 0.03);
            }
        </style>

        @if(count($items) > 0)
            <table class="custom-table">
                <thead>
                <tr>
                    <th>Med</th>
                    <th>Frequency</th>
                    <th>Interval</th>
                    <th>Times Per Interv</th>
                    <th>dosage</th>
                    <th>unit</th>
                    <th>Start</th>
                    <th>End</th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->frequency }}</td>
                        <td>{{ $item->interval }}</td>
                        <td>{{ $item->times_per_interval }}</td>
                        <td>{{ $item->dose_amount }}</td>
                        <td>{{ $item->dose_unit }}</td>
                        <td>{{ $item->start_date }}</td>
                        <td>{{ $item->end_date }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <span>لا توجد عناصر</span>
        @endif
    </div>
</x-dynamic-component>
