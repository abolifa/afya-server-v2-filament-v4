<?php


namespace App\Helpers;


use App\Models\Center;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\TransferInvoice;
use App\Models\TransferInvoiceItem;
use Carbon\Carbon;
use InvalidArgumentException;

class CommonHelpers
{
    public static function checkCenterSchedule(Center $center, ?string $date, ?string $time): bool
    {
        if (empty($date) || empty($time)) {
            return false;
        }
        $carbonDate = Carbon::parse($date);
        $carbonTime = Carbon::parse($time)->format('H:i:s');
        $day = strtolower($carbonDate->englishDayOfWeek);
        return $center->schedules()
            ->where('day', $day)
            ->where('is_active', true)
            ->where('start_time', '<=', $carbonTime)
            ->where('end_time', '>=', $carbonTime)
            ->exists();
    }


    public static function getStock(int $productId, ?int $centerId = null): int
    {
        $invoiceQty = InvoiceItem::where('product_id', $productId)
            ->whereHas('invoice', function ($q) use ($centerId) {
                $q->where('status', 'confirmed');
                if ($centerId) {
                    $q->where('center_id', $centerId);
                }
            })
            ->sum('quantity');
        $orderQty = OrderItem::where('product_id', $productId)
            ->whereHas('order', function ($q) use ($centerId) {
                $q->where('status', 'confirmed');
                if ($centerId) {
                    $q->where('center_id', $centerId);
                }
            })
            ->sum('quantity');
        if (!$centerId) {
            return $invoiceQty - $orderQty;
        }
        $transferIn = TransferInvoiceItem::where('product_id', $productId)
            ->whereHas('transferInvoice', function ($q) use ($centerId) {
                $q->where('status', 'confirmed')
                    ->where('to_center_id', $centerId);
            })
            ->sum('quantity');
        $transferOut = TransferInvoiceItem::where('product_id', $productId)
            ->whereHas('transferInvoice', function ($q) use ($centerId) {
                $q->where('status', 'confirmed')
                    ->where('from_center_id', $centerId);
            })
            ->sum('quantity');
        return $invoiceQty + $transferIn - $transferOut - $orderQty;
    }

    public static function getStockModel(int $productId, string $modelClass, ?int $centerId = null, string $statusField = 'status'): int
    {
        $relations = [
            Order::class => ['items' => OrderItem::class, 'foreign' => 'order_id'],
            Invoice::class => ['items' => InvoiceItem::class, 'foreign' => 'invoice_id'],
            TransferInvoice::class => ['items' => TransferInvoiceItem::class, 'foreign' => 'transfer_invoice_id'],
        ];

        if (!isset($relations[$modelClass])) {
            throw new InvalidArgumentException("Unsupported model [$modelClass]");
        }

        $itemModel = $relations[$modelClass]['items'];
        $foreignKey = $relations[$modelClass]['foreign'];

        return $itemModel::where('product_id', $productId)
            ->whereHas(
                $foreignKey === 'order_id' ? 'order' :
                    ($foreignKey === 'invoice_id' ? 'invoice' : 'transferInvoice'),
                function ($q) use ($statusField, $centerId, $foreignKey) {
                    $q->where($statusField, 'confirmed');

                    // filter by center if centerId is provided
                    if ($centerId) {
                        if (in_array($foreignKey, ['order_id', 'invoice_id'])) {
                            $q->where('center_id', $centerId);
                        } elseif ($foreignKey === 'transfer_invoice_id') {
                            // optional: handle transfers per center
                            $q->where(function ($q2) use ($centerId) {
                                $q2->where('from_center_id', $centerId)
                                    ->orWhere('to_center_id', $centerId);
                            });
                        }
                    }
                }
            )
            ->sum('quantity');
    }


    public static function getTransferStock(int $productId, ?int $centerId = null): array
    {
        $incoming = TransferInvoiceItem::where('product_id', $productId)
            ->when($centerId, fn($q) => $q->whereHas('transferInvoice', fn($q2) => $q2->where('status', 'confirmed')->where('to_center_id', $centerId)))
            ->sum('quantity');

        $outgoing = TransferInvoiceItem::where('product_id', $productId)
            ->when($centerId, fn($q) => $q->whereHas('transferInvoice', fn($q2) => $q2->where('status', 'confirmed')->where('from_center_id', $centerId)))
            ->sum('quantity');

        return [
            'in' => $incoming,
            'out' => $outgoing,
        ];
    }


}
