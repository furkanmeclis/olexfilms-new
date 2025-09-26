<?php

namespace App\Filament\Exports;

use App\Models\Dealer;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class DealerExporter extends Exporter
{
    protected static ?string $model = Dealer::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('user_id')
                ->label('Kullanıcı ID'),
            
            ExportColumn::make('user.name')
                ->label('Kullanıcı Adı'),
            
            ExportColumn::make('company_name')
                ->label('Şirket Adı'),
            
            ExportColumn::make('trade_name')
                ->label('Ticari Unvan'),
            
            ExportColumn::make('tax_number')
                ->label('Vergi Numarası'),
            
            ExportColumn::make('tax_office')
                ->label('Vergi Dairesi'),
            
            ExportColumn::make('phone')
                ->label('Telefon'),
            
            ExportColumn::make('mobile')
                ->label('Cep Telefonu'),
            
            ExportColumn::make('email')
                ->label('E-posta'),
            
            ExportColumn::make('website')
                ->label('Web Sitesi'),
            
            ExportColumn::make('address')
                ->label('Adres'),
            
            ExportColumn::make('city')
                ->label('Şehir'),
            
            ExportColumn::make('district')
                ->label('İlçe'),
            
            ExportColumn::make('postal_code')
                ->label('Posta Kodu'),
            
            ExportColumn::make('country')
                ->label('Ülke'),
            
            ExportColumn::make('latitude')
                ->label('Enlem'),
            
            ExportColumn::make('longitude')
                ->label('Boylam'),
            
            ExportColumn::make('location_name')
                ->label('Konum Adı'),
            
            ExportColumn::make('facebook_url')
                ->label('Facebook URL'),
            
            ExportColumn::make('instagram_url')
                ->label('Instagram URL'),
            
            ExportColumn::make('twitter_url')
                ->label('Twitter URL'),
            
            ExportColumn::make('linkedin_url')
                ->label('LinkedIn URL'),
            
            ExportColumn::make('youtube_url')
                ->label('YouTube URL'),
            
            ExportColumn::make('tiktok_url')
                ->label('TikTok URL'),
            
            ExportColumn::make('description')
                ->label('Açıklama'),
            
            ExportColumn::make('services')
                ->label('Hizmetler'),
            
            ExportColumn::make('working_hours')
                ->label('Çalışma Saatleri'),
            
            ExportColumn::make('established_year')
                ->label('Kuruluş Yılı'),
            
            ExportColumn::make('is_active')
                ->label('Aktif')
                ->formatStateUsing(fn ($state) => $state ? 'Evet' : 'Hayır'),
            
            ExportColumn::make('is_verified')
                ->label('Doğrulanmış')
                ->formatStateUsing(fn ($state) => $state ? 'Evet' : 'Hayır'),
            
            ExportColumn::make('verified_at')
                ->label('Doğrulama Tarihi')
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
        $body = 'Bayi export işlemi tamamlandı. ';
        
        if ($export->successful_rows) {
            $body .= "{$export->successful_rows} kayıt başarıyla export edildi.";
        }
        
        return $body;
    }
}
