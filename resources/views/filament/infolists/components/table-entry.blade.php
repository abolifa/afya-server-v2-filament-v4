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
                    <th>رقم</th>
                    <th>الصنف</th>
                    <th>وحدة القياس</th>
                    <th>الكمية</th>
                    <th>الإجمالي</th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->unit->name }}-{{ $item->unit->conversion_factor }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->quantity * $item->unit->conversion_factor }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <span>لا توجد عناصر</span>
        @endif
    </div>
</x-dynamic-component>
