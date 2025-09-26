<?php

namespace App\Filament\Exports;

use App\Models\ProductCode;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ProductCodeExporter extends Exporter
{
    protected static ?string $model = ProductCode::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),

            ExportColumn::make('code')
                ->label('Kod'),

            ExportColumn::make('product.name')
                ->label('Ürün Adı'),

            ExportColumn::make('user.name')
                ->label('Kullanıcı Adı'),

            ExportColumn::make('user.email')
                ->label('Kullanıcı E-posta'),

            ExportColumn::make('location_type')
                ->label('Lokasyon Tipi')
                ->formatStateUsing(fn ($state) => match($state) {
                    'merkez' => 'Merkez',
                    'bayi' => 'Bayi',
                    'depo' => 'Depo',
                    'kargo' => 'Kargo',
                    'musteri' => 'Müşteri',
                    default => $state,
                }),

            ExportColumn::make('location_name')
                ->label('Lokasyon Adı'),

            ExportColumn::make('quantity')
                ->label('Toplam Miktar'),

            ExportColumn::make('quantity_type')
                ->label('Miktar Tipi')
                ->formatStateUsing(fn ($state) => match($state) {
                    'm2' => 'M²',
                    'kutu' => 'Kutu',
                    'adet' => 'Adet',
                    'kg' => 'Kg',
                    'lt' => 'Lt',
                    default => $state,
                }),

            ExportColumn::make('used_quantity')
                ->label('Kullanılan Miktar'),

            ExportColumn::make('remaining_quantity')
                ->label('Kalan Miktar'),

            ExportColumn::make('is_active')
                ->label('Aktif')
                ->formatStateUsing(fn ($state) => $state ? 'Evet' : 'Hayır'),

            ExportColumn::make('notes')
                ->label('Notlar'),

            ExportColumn::make('created_at')
                ->label('Oluşturulma Tarihi')
                ->formatStateUsing(fn ($state) => $state->format('d/m/Y H:i')),

            ExportColumn::make('updated_at')
                ->label('Güncelleme Tarihi')
                ->formatStateUsing(fn ($state) => $state->format('d/m/Y H:i')),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Ürün kodu export işlemi tamamlandı. ';
        
        if ($export->successful_rows) {
            $body .= "{$export->successful_rows} kayıt başarıyla export edildi.";
        }
        
        return $body;
    }
}
