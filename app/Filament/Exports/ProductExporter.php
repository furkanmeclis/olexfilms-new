<?php

namespace App\Filament\Exports;

use App\Models\Product;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class ProductExporter extends Exporter
{
    protected static ?string $model = Product::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            
            ExportColumn::make('name')
                ->label('Ürün Adı'),
            
            ExportColumn::make('sku')
                ->label('SKU'),
            
            ExportColumn::make('description')
                ->label('Açıklama'),
            
            ExportColumn::make('specifications')
                ->label('Teknik Özellikler'),
            
            ExportColumn::make('price')
                ->label('Fiyat')
                ->formatStateUsing(fn ($state) => number_format($state, 2) . ' ₺'),
            
            ExportColumn::make('warranty_months')
                ->label('Garanti Süresi (Ay)')
                ->formatStateUsing(fn ($state) => $state . ' Ay'),
            
            ExportColumn::make('category.name')
                ->label('Kategori Adı'),
            
            ExportColumn::make('is_active')
                ->label('Aktif')
                ->formatStateUsing(fn ($state) => $state ? 'Evet' : 'Hayır'),
            
            ExportColumn::make('sort_order')
                ->label('Sıralama'),
            
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
        $body = 'Ürün export işlemi tamamlandı. ';
        
        if ($export->successful_rows) {
            $body .= "{$export->successful_rows} kayıt başarıyla export edildi.";
        }
        
        return $body;
    }
}
