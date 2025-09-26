<?php

namespace App\Filament\Imports;

use App\Models\Product;
use App\Models\ProductCategory;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class ProductImporter extends Importer
{
    protected static ?string $model = Product::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->label('Ürün Adı')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            
            ImportColumn::make('sku')
                ->label('SKU')
                ->rules(['nullable', 'string', 'max:255', 'unique:products,sku']),
            
            ImportColumn::make('description')
                ->label('Açıklama')
                ->rules(['nullable', 'string']),
            
            ImportColumn::make('specifications')
                ->label('Teknik Özellikler')
                ->rules(['nullable', 'string']),
            
            ImportColumn::make('price')
                ->label('Fiyat')
                ->requiredMapping()
                ->rules(['required', 'numeric', 'min:0']),
            
            ImportColumn::make('warranty_months')
                ->label('Garanti Süresi (Ay)')
                ->requiredMapping()
                ->rules(['required', 'integer', 'min:1', 'max:120']),
            
            ImportColumn::make('category_name')
                ->label('Kategori Adı')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            
            ImportColumn::make('is_active')
                ->label('Aktif')
                ->rules(['nullable', 'boolean']),
            
            ImportColumn::make('sort_order')
                ->label('Sıralama')
                ->rules(['nullable', 'integer', 'min:0'])
        ];
    }

    public function resolveRecord(): Product
    {
        return new Product();
    }

    protected function afterSave(): void
    {
        // Kategori ilişkisini kur
        if (isset($this->data['category_name'])) {
            $category = ProductCategory::where('name', $this->data['category_name'])->first();
            if ($category) {
                $this->record->product_category_id = $category->id;
                $this->record->save();
            }
        }
        
        // SKU otomatik oluşturulması
        if (empty($this->record->sku)) {
            $this->record->sku = 'PRD-' . strtoupper(\Illuminate\Support\Str::random(8));
            $this->record->save();
        }
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Ürün import işlemi tamamlandı. ';
        
        if ($import->successful_rows) {
            $body .= "{$import->successful_rows} kayıt başarıyla import edildi. ";
        }
        
        if ($import->getFailedRowsCount()) {
            $body .= "{$import->getFailedRowsCount()} kayıt başarısız oldu.";
        }
        
        return $body;
    }
}
