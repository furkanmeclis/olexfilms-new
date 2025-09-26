<?php

namespace App\Filament\Imports;

use App\Models\CarBrand;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class CarBrandImporter extends Importer
{
    protected static ?string $model = CarBrand::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->label('Marka Adı')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            
            ImportColumn::make('external_id')
                ->label('Dış ID')
                ->rules(['nullable', 'string', 'max:255', 'unique:car_brands,external_id']),
            
            ImportColumn::make('logo')
                ->label('Logo URL')
                ->rules(['nullable', 'url']),
            
            ImportColumn::make('is_active')
                ->label('Aktif')
                ->rules(['nullable', 'boolean'])
                ->default(true),
        ];
    }

    public function resolveRecord(): CarBrand
    {
        return new CarBrand();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Araç markası import işlemi tamamlandı. ';
        
        if ($import->successful_rows) {
            $body .= "{$import->successful_rows} kayıt başarıyla import edildi. ";
        }
        
        if ($import->failed_rows) {
            $body .= "{$import->failed_rows} kayıt başarısız oldu.";
        }
        
        return $body;
    }
}
