<?php

namespace App\Filament\Imports;

use App\Models\ProductCode;
use App\Models\Product;
use App\Models\User;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class ProductCodeImporter extends Importer
{
    protected static ?string $model = ProductCode::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('code')
                ->label('Kod')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255', 'unique:product_codes,code'])
                ->helperText('Ürün kodu (benzersiz olmalı)'),

            ImportColumn::make('product_name')
                ->label('Ürün Adı')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255'])
                ->helperText('Ürün adı'),

            ImportColumn::make('user_email')
                ->label('Kullanıcı E-posta')
                ->requiredMapping()
                ->rules(['required', 'email', 'exists:users,email'])
                ->helperText('Kod sahibi kullanıcının e-posta adresi'),

            ImportColumn::make('location_type')
                ->label('Lokasyon Tipi')
                ->requiredMapping()
                ->rules(['required', 'in:merkez,bayi,depo,kargo,musteri'])
                ->helperText('Lokasyon tipi (merkez, bayi, depo, kargo, musteri)'),

            ImportColumn::make('location_name')
                ->label('Lokasyon Adı')
                ->rules(['nullable', 'string', 'max:255'])
                ->helperText('Lokasyon detay adı (opsiyonel)'),

            ImportColumn::make('quantity')
                ->label('Miktar')
                ->requiredMapping()
                ->rules(['required', 'numeric', 'min:0'])
                ->helperText('Toplam miktar'),

            ImportColumn::make('quantity_type')
                ->label('Miktar Tipi')
                ->requiredMapping()
                ->rules(['required', 'in:m2,kutu,adet,kg,lt'])
                ->helperText('Miktar birimi (m2, kutu, adet, kg, lt)'),

            ImportColumn::make('used_quantity')
                ->label('Kullanılan Miktar')
                ->rules(['nullable', 'numeric', 'min:0'])
                ->helperText('Kullanılan miktar (varsayılan: 0)'),

            ImportColumn::make('notes')
                ->label('Notlar')
                ->rules(['nullable', 'string'])
                ->helperText('Ek notlar'),

            ImportColumn::make('is_active')
                ->label('Aktif')
                ->rules(['nullable', 'boolean'])
                ->helperText('Kod aktif mi? (varsayılan: true)'),
        ];
    }

    public function resolveRecord(): ProductCode
    {
        return new ProductCode();
    }

    protected function afterSave(): void
    {
        // Ürün ID'sini bul ve ata
        if (isset($this->data['product_name'])) {
            $product = Product::where('name', $this->data['product_name'])->first();
            if ($product) {
                $this->record->product_id = $product->id;
            }
        }

        // Kullanıcı ID'sini bul ve ata
        if (isset($this->data['user_email'])) {
            $user = User::where('email', $this->data['user_email'])->first();
            if ($user) {
                $this->record->user_id = $user->id;
            }
        }

        // Varsayılan değerleri ayarla
        if (!isset($this->data['used_quantity'])) {
            $this->record->used_quantity = 0;
        }

        if (!isset($this->data['is_active'])) {
            $this->record->is_active = true;
        }

        $this->record->save();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Ürün kodu import işlemi tamamlandı. ';
        
        if ($import->successful_rows) {
            $body .= "{$import->successful_rows} kayıt başarıyla import edildi. ";
        }
        
        if ($import->failed_rows) {
            $body .= "{$import->failed_rows} kayıt başarısız oldu.";
        }
        
        return $body;
    }
}
