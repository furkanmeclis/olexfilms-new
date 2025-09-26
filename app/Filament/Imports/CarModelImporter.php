<?php

namespace App\Filament\Imports;

use App\Models\CarModel;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class CarModelImporter extends Importer
{
    protected static ?string $model = CarModel::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('brand_name')
                ->label('Marka Adı')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            
            ImportColumn::make('name')
                ->label('Model Adı')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            
            ImportColumn::make('external_id')
                ->label('Dış ID')
                ->rules(['nullable', 'string', 'max:255', 'unique:car_models,external_id']),
            
            ImportColumn::make('is_active')
                ->label('Aktif')
                ->rules(['nullable', 'boolean'])
                ->default(true),
        ];
    }

    public function resolveRecord(): CarModel
    {
        return new CarModel();
    }

    protected function afterSave(): void
    {
        // Marka adından brand_id'yi çöz
        if (isset($this->data['brand_name'])) {
            $brand = \App\Models\CarBrand::where('name', $this->data['brand_name'])->first();
            if ($brand) {
                $this->record->brand_id = $brand->id;
                $this->record->save();
            }
        }
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Araç modeli import işlemi tamamlandı. ';
        
        if ($import->successful_rows) {
            $body .= "{$import->successful_rows} kayıt başarıyla import edildi. ";
        }
        
        if ($import->failed_rows) {
            $body .= "{$import->failed_rows} kayıt başarısız oldu.";
        }
        
        return $body;
    }
}
