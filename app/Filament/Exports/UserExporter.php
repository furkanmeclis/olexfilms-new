<?php

namespace App\Filament\Exports;

use App\Models\User;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class UserExporter extends Exporter
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            
            ExportColumn::make('name')
                ->label('Ad Soyad'),
            
            ExportColumn::make('email')
                ->label('E-posta'),
            
            ExportColumn::make('dealer_id')
                ->label('Bayi ID'),
            
            ExportColumn::make('dealer.name')
                ->label('Bayi Adı'),
            
            ExportColumn::make('roles')
                ->label('Roller')
                ->formatStateUsing(fn ($record) => $record->roles->pluck('name')->implode(', ')),
            
            ExportColumn::make('email_verified_at')
                ->label('E-posta Doğrulama Tarihi')
                ->formatStateUsing(fn ($state) => $state ? $state->format('d/m/Y H:i') : 'Doğrulanmamış'),
            
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
        $body = 'Kullanıcı export işlemi tamamlandı. ';
        
        if ($export->successful_rows) {
            $body .= "{$export->successful_rows} kayıt başarıyla export edildi.";
        }
        
        return $body;
    }
}
