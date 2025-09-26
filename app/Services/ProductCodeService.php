<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductCode;
use App\Models\Transfer;
use App\Models\TransferItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProductCodeService
{
    /**
     * Ürün kodu oluştur
     */
    public function createProductCode(array $data): ProductCode
    {
        return DB::transaction(function () use ($data) {
            $productCode = ProductCode::create([
                'code' => $data['code'] ?? null,
                'product_id' => $data['product_id'],
                'user_id' => $data['user_id'] ?? auth()->id(),
                'location_type' => $data['location_type'],
                'location_name' => $data['location_name'] ?? null,
                'quantity' => $data['quantity'],
                'quantity_type' => $data['quantity_type'],
                'notes' => $data['notes'] ?? null,
                'is_active' => $data['is_active'] ?? true,
            ]);

            return $productCode;
        });
    }

    /**
     * Toplu ürün kodu oluştur
     */
    public function createBulkProductCodes(array $data): Collection
    {
        return DB::transaction(function () use ($data) {
            $productCodes = collect();

            foreach ($data as $item) {
                $productCode = $this->createProductCode($item);
                $productCodes->push($productCode);
            }

            return $productCodes;
        });
    }

    /**
     * Ürün kodunu kullan
     */
    public function useProductCode(string $code, float $quantity, string $notes = null): bool
    {
        $productCode = ProductCode::where('code', $code)->first();

        if (!$productCode) {
            return false;
        }

        return $productCode->useQuantity($quantity, $notes);
    }

    /**
     * Ürün kodunu transfer et
     */
    public function transferProductCode(string $code, string $newLocationType, string $newLocationName = null, string $notes = null): bool
    {
        $productCode = ProductCode::where('code', $code)->first();

        if (!$productCode) {
            return false;
        }

        return $productCode->transferTo($newLocationType, $newLocationName, $notes);
    }

    /**
     * Toplu transfer oluştur
     */
    public function createBulkTransfer(array $data): Transfer
    {
        return DB::transaction(function () use ($data) {
            $transfer = Transfer::create([
                'from_user_id' => $data['from_user_id'],
                'to_user_id' => $data['to_user_id'],
                'notes' => $data['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);

            foreach ($data['items'] as $item) {
                TransferItem::create([
                    'transfer_id' => $transfer->id,
                    'product_code_id' => $item['product_code_id'],
                    'quantity' => $item['quantity'],
                    'quantity_type' => $item['quantity_type'],
                    'notes' => $item['notes'] ?? null,
                ]);
            }

            return $transfer;
        });
    }

    /**
     * Transfer durumunu güncelle
     */
    public function updateTransferStatus(int $transferId, string $status, array $data = []): bool
    {
        $transfer = Transfer::find($transferId);

        if (!$transfer) {
            return false;
        }

        switch ($status) {
            case 'yolda':
                return $transfer->markAsSent($data['cargo_code'] ?? null, $data['cargo_company'] ?? null);
            case 'teslim_edildi':
                return $transfer->markAsDelivered();
            case 'iptal':
                return $transfer->cancel();
            default:
                return false;
        }
    }

    /**
     * Ürün kodlarını filtrele
     */
    public function getProductCodes(array $filters = []): Collection
    {
        $query = ProductCode::with(['product', 'user']);

        if (isset($filters['product_id'])) {
            $query->where('product_id', $filters['product_id']);
        }

        if (isset($filters['location_type'])) {
            $query->where('location_type', $filters['location_type']);
        }

        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('code', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('location_name', 'like', '%' . $filters['search'] . '%')
                  ->orWhereHas('product', function ($productQuery) use ($filters) {
                      $productQuery->where('name', 'like', '%' . $filters['search'] . '%');
                  });
            });
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Ürün stok durumunu hesapla
     */
    public function getProductStockStatus(int $productId): array
    {
        $productCodes = ProductCode::where('product_id', $productId)
            ->where('is_active', true)
            ->get();

        $totalQuantity = $productCodes->sum('quantity');
        $totalUsed = $productCodes->sum('used_quantity');
        $totalRemaining = $totalQuantity - $totalUsed;

        $locationBreakdown = $productCodes->groupBy('location_type')->map(function ($codes) {
            return [
                'total_quantity' => $codes->sum('quantity'),
                'total_used' => $codes->sum('used_quantity'),
                'total_remaining' => $codes->sum('quantity') - $codes->sum('used_quantity'),
                'count' => $codes->count(),
            ];
        });

        return [
            'total_quantity' => $totalQuantity,
            'total_used' => $totalUsed,
            'total_remaining' => $totalRemaining,
            'location_breakdown' => $locationBreakdown,
            'product_codes_count' => $productCodes->count(),
        ];
    }

    /**
     * Ürün kodlarını export için hazırla
     */
    public function prepareForExport(Collection $productCodes): array
    {
        return $productCodes->map(function ($productCode) {
            return [
                'Kod' => $productCode->code,
                'Ürün' => $productCode->product->name,
                'Lokasyon' => $productCode->location_display,
                'Miktar' => $productCode->formatted_quantity,
                'Kullanılan' => number_format($productCode->used_quantity, 2) . ' ' . $productCode->quantity_type,
                'Kalan' => $productCode->formatted_remaining_quantity,
                'Durum' => $productCode->is_active ? 'Aktif' : 'Pasif',
                'Oluşturulma Tarihi' => $productCode->created_at->format('d/m/Y H:i'),
            ];
        })->toArray();
    }

    /**
     * Import için ürün kodlarını işle
     */
    public function processImportData(array $data): array
    {
        $processed = [];
        $errors = [];

        foreach ($data as $index => $row) {
            try {
                // Ürün kontrolü
                $product = Product::where('name', $row['product_name'])->first();
                if (!$product) {
                    $errors[] = "Satır {$index}: Ürün bulunamadı - {$row['product_name']}";
                    continue;
                }

                // Kullanıcı kontrolü
                $user = auth()->user();
                if (isset($row['user_email'])) {
                    $user = \App\Models\User::where('email', $row['user_email'])->first();
                    if (!$user) {
                        $errors[] = "Satır {$index}: Kullanıcı bulunamadı - {$row['user_email']}";
                        continue;
                    }
                }

                $processed[] = [
                    'code' => $row['code'] ?? null,
                    'product_id' => $product->id,
                    'user_id' => $user->id,
                    'location_type' => $row['location_type'],
                    'location_name' => $row['location_name'] ?? null,
                    'quantity' => $row['quantity'],
                    'quantity_type' => $row['quantity_type'],
                    'notes' => $row['notes'] ?? null,
                    'is_active' => $row['is_active'] ?? true,
                ];
            } catch (\Exception $e) {
                $errors[] = "Satır {$index}: {$e->getMessage()}";
            }
        }

        return [
            'processed' => $processed,
            'errors' => $errors,
        ];
    }
}
