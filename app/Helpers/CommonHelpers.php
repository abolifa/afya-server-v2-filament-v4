<?php

namespace App\Helpers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\TransferInvoice;
use App\Models\TransferInvoiceItem;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class CommonHelpers
{
    public static function canTakeFromStock(
        int  $productId,
        int  $centerId,
        int  $requestedQty,
        ?int $unitId = null,
        int  $existingQty = 0,
        ?int $existingUnitId = null
    ): bool
    {
        $availablePieces = self::getStock($productId, $centerId);

        $factor = self::unitFactor($unitId);
        $requestedPieces = $requestedQty * $factor;

        if ($existingQty > 0) {
            $oldFactor = self::unitFactor($existingUnitId);
            $availablePieces += $existingQty * $oldFactor;
        }

        return $requestedPieces <= $availablePieces;
    }

    /**
     * Total stock in base pieces.
     * - Invoices add (+)
     * - Orders subtract (-)
     * - Transfers in/out adjust per center when $centerId is provided
     * - Counts all statuses EXCEPT 'cancelled' (NULL counts as active)
     */
    public static function getStock(int $productId, ?int $centerId = null): int
    {
        // Invoices (+)
        $invoicePieces = InvoiceItem::query()
            ->where('product_id', $productId)
            ->whereHas('invoice', function ($q) use ($centerId) {
                self::scopeNotCancelled($q, 'status');
                if ($centerId) {
                    $q->where('center_id', $centerId);
                }
            })
            ->leftJoin('units', 'units.id', '=', 'invoice_items.unit_id')
            ->sum(DB::raw('invoice_items.quantity * COALESCE(units.conversion_factor, 1)'));

        // Orders (−)
        $orderPieces = OrderItem::query()
            ->where('product_id', $productId)
            ->whereHas('order', function ($q) use ($centerId) {
                self::scopeNotCancelled($q, 'status');        // <- changed
                if ($centerId) {
                    $q->where('center_id', $centerId);
                }
            })
            ->leftJoin('units', 'units.id', '=', 'order_items.unit_id')
            ->sum(DB::raw('order_items.quantity * COALESCE(units.conversion_factor, 1)'));

        if (!$centerId) {
            return (int)round($invoicePieces - $orderPieces);
        }

        // Transfers IN to this center (+)
        $transferInPieces = TransferInvoiceItem::query()
            ->where('product_id', $productId)
            ->whereHas('transferInvoice', function ($q) use ($centerId) {
                self::scopeNotCancelled($q, 'status');        // <- changed
                $q->where('to_center_id', $centerId);
            })
            ->leftJoin('units', 'units.id', '=', 'transfer_invoice_items.unit_id')
            ->sum(DB::raw('transfer_invoice_items.quantity * COALESCE(units.conversion_factor, 1)'));

        // Transfers OUT from this center (−)
        $transferOutPieces = TransferInvoiceItem::query()
            ->where('product_id', $productId)
            ->whereHas('transferInvoice', function ($q) use ($centerId) {
                self::scopeNotCancelled($q, 'status');        // <- changed
                $q->where('from_center_id', $centerId);
            })
            ->leftJoin('units', 'units.id', '=', 'transfer_invoice_items.unit_id')
            ->sum(DB::raw('transfer_invoice_items.quantity * COALESCE(units.conversion_factor, 1)'));

        return (int)round($invoicePieces + $transferInPieces - $transferOutPieces - $orderPieces);
    }

    // ... checkCenterSchedule unchanged ...

    /**
     * Apply "all except cancelled" on a model that has a status column.
     * Treat NULL status as active (counts toward stock).
     */
    protected static function scopeNotCancelled($query, string $statusField = 'status')
    {
        return $query->where(fn($q) => $q->whereNull($statusField)->orWhere($statusField, '!=', 'cancelled')
        );
    }

    public static function unitFactor(?int $unitId): int
    {
        return (int)(Unit::find($unitId)?->conversion_factor ?? 1);
    }

    public static function maxAllowedQuantity(
        int  $productId,
        int  $centerId,
        ?int $unitId,
        int  $existingQty = 0,
        ?int $existingUnitId = null
    ): int
    {
        $availablePieces = self::getStock($productId, $centerId);

        if ($existingQty > 0) {
            $availablePieces += $existingQty * self::unitFactor($existingUnitId);
        }

        $factor = self::unitFactor($unitId);

        return intdiv($availablePieces, max(1, $factor));
    }

    /**
     * Generic stock sum by model, counting all statuses EXCEPT 'cancelled'.
     * $statusField is the status column name on the parent model.
     */
    public static function getStockModel(
        int    $productId,
        string $modelClass,
        ?int   $centerId = null,
        string $statusField = 'status'
    ): int
    {
        $relations = [
            Order::class => [
                'item' => OrderItem::class,
                'item_table' => (new OrderItem)->getTable(),
                'parent_rel' => 'order',
                'parent_key' => 'order_id',
                'center_col' => 'center_id',
            ],
            Invoice::class => [
                'item' => InvoiceItem::class,
                'item_table' => (new InvoiceItem)->getTable(),
                'parent_rel' => 'invoice',
                'parent_key' => 'invoice_id',
                'center_col' => 'center_id',
            ],
            TransferInvoice::class => [
                'item' => TransferInvoiceItem::class,
                'item_table' => (new TransferInvoiceItem)->getTable(),
                'parent_rel' => 'transferInvoice',
                'parent_key' => 'transfer_invoice_id',
                'center_col' => null, // special handling (from/to)
            ],
        ];

        if (!isset($relations[$modelClass])) {
            throw new InvalidArgumentException("Unsupported model [$modelClass]");
        }

        $conf = $relations[$modelClass];
        $itemModel = $conf['item'];
        $itemTable = $conf['item_table'];
        $parentRel = $conf['parent_rel'];

        $query = $itemModel::query()
            ->where("$itemTable.product_id", $productId)
            ->whereHas($parentRel, function ($q) use ($statusField, $centerId, $conf) {
                self::scopeNotCancelled($q, $statusField);    // <- changed

                if ($centerId) {
                    if ($conf['center_col']) {
                        $q->where($conf['center_col'], $centerId);
                    } else {
                        // transfers: include rows where this center is either side
                        $q->where(function ($q2) use ($centerId) {
                            $q2->where('from_center_id', $centerId)
                                ->orWhere('to_center_id', $centerId);
                        });
                    }
                }
            })
            ->leftJoin('units', 'units.id', '=', "$itemTable.unit_id");

        $sum = $query->sum(DB::raw("$itemTable.quantity * COALESCE(units.conversion_factor, 1)"));

        return (int)round($sum);
    }

    // getTransferStock unchanged in spirit; but if you want it consistent too, add scopeNotCancelled on the parent relation:
    public static function getTransferStock(int $productId, ?int $centerId = null): array
    {
        $in = TransferInvoiceItem::query()
            ->where('product_id', $productId)
            ->when($centerId, fn($q) => $q->whereHas(
                'transferInvoice',
                fn($q2) => self::scopeNotCancelled($q2, 'status')->where('to_center_id', $centerId) // <- changed
            ))
            ->leftJoin('units', 'units.id', '=', 'transfer_invoice_items.unit_id')
            ->sum(DB::raw('transfer_invoice_items.quantity * COALESCE(units.conversion_factor, 1)'));

        $out = TransferInvoiceItem::query()
            ->where('product_id', $productId)
            ->when($centerId, fn($q) => $q->whereHas(
                'transferInvoice',
                fn($q2) => self::scopeNotCancelled($q2, 'status')->where('from_center_id', $centerId) // <- changed
            ))
            ->leftJoin('units', 'units.id', '=', 'transfer_invoice_items.unit_id')
            ->sum(DB::raw('transfer_invoice_items.quantity * COALESCE(units.conversion_factor, 1)'));

        return ['in' => (int)round($in), 'out' => (int)round($out)];
    }
}
