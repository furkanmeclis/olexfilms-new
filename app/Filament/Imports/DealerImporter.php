<?php

namespace App\Filament\Imports;

use App\Models\Dealer;
use App\Models\User;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class DealerImporter extends Importer
{
    protected static ?string $model = Dealer::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('user_id')
                ->label('Kullanıcı ID')
                ->requiredMapping()
                ->rules(['required', 'integer', 'exists:users,id']),
            
            ImportColumn::make('company_name')
                ->label('Şirket Adı')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            
            ImportColumn::make('trade_name')
                ->label('Ticari Unvan')
                ->rules(['nullable', 'string', 'max:255']),
            
            ImportColumn::make('tax_number')
                ->label('Vergi Numarası')
                ->rules(['nullable', 'string', 'max:20']),
            
            ImportColumn::make('tax_office')
                ->label('Vergi Dairesi')
                ->rules(['nullable', 'string', 'max:255']),
            
            ImportColumn::make('phone')
                ->label('Telefon')
                ->rules(['nullable', 'string', 'max:20']),
            
            ImportColumn::make('mobile')
                ->label('Cep Telefonu')
                ->rules(['nullable', 'string', 'max:20']),
            
            ImportColumn::make('email')
                ->label('E-posta')
                ->rules(['nullable', 'email', 'max:255']),
            
            ImportColumn::make('website')
                ->label('Web Sitesi')
                ->rules(['nullable', 'url', 'max:255']),
            
            ImportColumn::make('address')
                ->label('Adres')
                ->rules(['nullable', 'string']),
            
            ImportColumn::make('city')
                ->label('Şehir')
                ->rules(['nullable', 'string', 'max:100']),
            
            ImportColumn::make('district')
                ->label('İlçe')
                ->rules(['nullable', 'string', 'max:100']),
            
            ImportColumn::make('postal_code')
                ->label('Posta Kodu')
                ->rules(['nullable', 'string', 'max:10']),
            
            ImportColumn::make('country')
                ->label('Ülke')
                ->rules(['nullable', 'string', 'max:100']),
            
            ImportColumn::make('latitude')
                ->label('Enlem')
                ->rules(['nullable', 'numeric', 'between:-90,90']),
            
            ImportColumn::make('longitude')
                ->label('Boylam')
                ->rules(['nullable', 'numeric', 'between:-180,180']),
            
            ImportColumn::make('location_name')
                ->label('Konum Adı')
                ->rules(['nullable', 'string', 'max:255']),
            
            ImportColumn::make('facebook_url')
                ->label('Facebook URL')
                ->rules(['nullable', 'url', 'max:255']),
            
            ImportColumn::make('instagram_url')
                ->label('Instagram URL')
                ->rules(['nullable', 'url', 'max:255']),
            
            ImportColumn::make('twitter_url')
                ->label('Twitter URL')
                ->rules(['nullable', 'url', 'max:255']),
            
            ImportColumn::make('linkedin_url')
                ->label('LinkedIn URL')
                ->rules(['nullable', 'url', 'max:255']),
            
            ImportColumn::make('youtube_url')
                ->label('YouTube URL')
                ->rules(['nullable', 'url', 'max:255']),
            
            ImportColumn::make('tiktok_url')
                ->label('TikTok URL')
                ->rules(['nullable', 'url', 'max:255']),
            
            ImportColumn::make('description')
                ->label('Açıklama')
                ->rules(['nullable', 'string']),
            
            ImportColumn::make('services')
                ->label('Hizmetler')
                ->rules(['nullable', 'string']),
            
            ImportColumn::make('working_hours')
                ->label('Çalışma Saatleri')
                ->rules(['nullable', 'string']),
            
            ImportColumn::make('established_year')
                ->label('Kuruluş Yılı')
                ->rules(['nullable', 'string', 'max:4']),
            
            ImportColumn::make('is_active')
                ->label('Aktif')
                ->rules(['nullable', 'boolean']),
            
            ImportColumn::make('is_verified')
                ->label('Doğrulanmış')
                ->rules(['nullable', 'boolean']),
        ];
    }

    public function resolveRecord(): ?Dealer
    {
        return Dealer::firstOrNew([
            'user_id' => $this->data['user_id'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Bayi import işlemi tamamlandı. ';
        
        if ($import->successful_rows) {
            $body .= "{$import->successful_rows} kayıt başarıyla import edildi. ";
        }
        
        if ($import->failed_rows) {
            $body .= "{$import->failed_rows} kayıt başarısız oldu.";
        }
        
        return $body;
    }
}
