<?php

namespace App\Filament\Exports;

use App\Models\CarModel;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class CarModelExporter extends Exporter
{
    protected static ?string $model = CarModel::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            
            ExportColumn::make('brand.name')
                ->label('Marka Adı'),
            
            ExportColumn::make('name')
                ->label('Model Adı'),
            
            ExportColumn::make('external_id')
                ->label('Dış ID'),
            
            ExportColumn::make('is_active')
                ->label('Aktif')
                ->formatStateUsing(fn ($state) => $state ? 'Evet' : 'Hayır'),
            
            ExportColumn::make('last_update')
                ->label('Son Güncelleme')
                ->formatStateUsing(fn ($state) => $state ? $state->format('d/m/Y H:i') : ''),
            
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
        $body = 'Araç modeli export işlemi tamamlandı. ';
        
        if ($export->successful_rows) {
            $body .= "{$export->successful_rows} kayıt başarıyla export edildi.";
        }
        
        return $body;
    }
}
