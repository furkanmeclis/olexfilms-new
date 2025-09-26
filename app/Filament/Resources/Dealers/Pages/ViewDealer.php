<?php

namespace App\Filament\Resources\Dealers\Pages;

use App\Filament\Resources\Dealers\DealerResource;
use Filament\Actions\EditAction;
use Filament\Schemas\Schema;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab as TabsTab;

class ViewDealer extends ViewRecord
{
    protected static string $resource = DealerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Tabs::make('Bayi Profili')
                    ->tabs([
                        TabsTab::make('Genel Bilgiler')
                            ->schema([
                               Section::make('Temel Bilgiler')
                                    ->schema([
                                        ImageEntry::make('logo_path')
                                            ->label('Logo')
                                            ->circular()
                                            ->size(80),
                                        TextEntry::make('company_name')
                                            ->label('Şirket Adı')
                                            ->weight('bold')
                                            ->size('lg'),
                                        TextEntry::make('user.name')
                                            ->label('Kullanıcı')
                                            ->badge()
                                            ->color('info'),
                                        TextEntry::make('trade_name')
                                            ->label('Ticari Unvan'),
                                        TextEntry::make('tax_number')
                                            ->label('Vergi Numarası')
                                            ->copyable(),
                                        TextEntry::make('tax_office')
                                            ->label('Vergi Dairesi'),
                                        TextEntry::make('established_year')
                                            ->label('Kuruluş Yılı')
                                            ->numeric(),
                                    ])
                                    ->columns(2),
                                
                                Section::make('Durum')
                                    ->schema([
                                        IconEntry::make('is_active')
                                            ->label('Aktif Durumu')
                                            ->boolean()
                                            ->trueIcon('heroicon-o-check-circle')
                                            ->falseIcon('heroicon-o-x-circle')
                                            ->trueColor('success')
                                            ->falseColor('danger'),
                                        IconEntry::make('is_verified')
                                            ->label('Doğrulama Durumu')
                                            ->boolean()
                                            ->trueIcon('heroicon-o-shield-check')
                                            ->falseIcon('heroicon-o-shield-exclamation')
                                            ->trueColor('success')
                                            ->falseColor('warning'),
                                        TextEntry::make('verified_at')
                                            ->label('Doğrulama Tarihi')
                                            ->dateTime('d/m/Y H:i')
                                            ->placeholder('Doğrulanmamış'),
                                    ])
                                    ->columns(3),
                            ]),
                        
                        TabsTab::make('İletişim Bilgileri')
                            ->schema([
                                Section::make('İletişim')
                                    ->schema([
                                        TextEntry::make('phone')
                                            ->label('Telefon')
                                            ->icon('heroicon-o-phone')
                                            ->copyable(),
                                        TextEntry::make('mobile')
                                            ->label('Cep Telefonu')
                                            ->icon('heroicon-o-device-phone-mobile')
                                            ->copyable(),
                                        TextEntry::make('email')
                                            ->label('E-posta')
                                            ->icon('heroicon-o-envelope')
                                            ->copyable()
                                            ->url(fn ($record) => $record->email ? "mailto:{$record->email}" : null),
                                        TextEntry::make('website')
                                            ->label('Web Sitesi')
                                            ->icon('heroicon-o-globe-alt')
                                            ->openUrlInNewTab(),
                                    ])
                                    ->columns(2),
                                
                                Section::make('Adres Bilgileri')
                                    ->schema([
                                        TextEntry::make('full_address')
                                            ->label('Tam Adres')
                                            ->icon('heroicon-o-map-pin')
                                            ->columnSpanFull(),
                                        TextEntry::make('city')
                                            ->label('Şehir')
                                            ->badge()
                                            ->color('info'),
                                        TextEntry::make('district')
                                            ->label('İlçe')
                                            ->badge()
                                            ->color('gray'),
                                        TextEntry::make('postal_code')
                                            ->label('Posta Kodu'),
                                        TextEntry::make('country')
                                            ->label('Ülke')
                                            ->badge()
                                            ->color('success'),
                                    ])
                                    ->columns(4),
                                
                                Section::make('Konum Bilgileri')
                                    ->schema([
                                        TextEntry::make('location_name')
                                            ->label('Konum Adı')
                                            ->icon('heroicon-o-building-office'),
                                        TextEntry::make('latitude')
                                            ->label('Enlem')
                                            ->numeric(decimalPlaces: 6)
                                            ->copyable(),
                                        TextEntry::make('longitude')
                                            ->label('Boylam')
                                            ->numeric(decimalPlaces: 6)
                                            ->copyable(),
                                    ])
                                    ->columns(3),
                            ]),
                        
                        TabsTab::make('Sosyal Medya')
                            ->schema([
                                Section::make('Sosyal Medya Hesapları')
                                    ->schema([
                                        TextEntry::make('facebook_url')
                                            ->label('Facebook')
                                            ->icon('heroicon-o-facebook')
                                            ->openUrlInNewTab()
                                            ->placeholder('Belirtilmemiş'),
                                        TextEntry::make('instagram_url')
                                            ->label('Instagram')
                                            ->icon('heroicon-o-camera')
                                            ->openUrlInNewTab()
                                            ->placeholder('Belirtilmemiş'),
                                        TextEntry::make('twitter_url')
                                            ->label('Twitter')
                                            ->icon('heroicon-o-chat-bubble-left-right')
                                            ->openUrlInNewTab()
                                            ->placeholder('Belirtilmemiş'),
                                        TextEntry::make('linkedin_url')
                                            ->label('LinkedIn')
                                            ->icon('heroicon-o-briefcase')
                                            ->openUrlInNewTab()
                                            ->placeholder('Belirtilmemiş'),
                                        TextEntry::make('youtube_url')
                                            ->label('YouTube')
                                            ->icon('heroicon-o-play')
                                            ->openUrlInNewTab()
                                            ->placeholder('Belirtilmemiş'),
                                        TextEntry::make('tiktok_url')
                                            ->label('TikTok')
                                            ->icon('heroicon-o-musical-note')
                                            ->openUrlInNewTab()
                                            ->placeholder('Belirtilmemiş'),
                                    ])
                                    ->columns(2),
                            ]),
                        
                        TabsTab::make('İş Bilgileri')
                            ->schema([
                                Section::make('Açıklamalar')
                                    ->schema([
                                        TextEntry::make('description')
                                            ->label('Bayi Açıklaması')
                                            ->columnSpanFull()
                                            ->markdown(),
                                        TextEntry::make('services')
                                            ->label('Hizmetler')
                                            ->columnSpanFull()
                                            ->markdown(),
                                        TextEntry::make('working_hours')
                                            ->label('Çalışma Saatleri')
                                            ->columnSpanFull()
                                            ->markdown(),
                                    ]),
                                
                                Section::make('Çalışanlar')
                                    ->schema([
                                        TextEntry::make('workers_count')
                                            ->label('Toplam Çalışan Sayısı')
                                            ->numeric()
                                            ->badge()
                                            ->color('success'),
                                        TextEntry::make('workers.name')
                                            ->label('Çalışanlar')
                                            ->listWithLineBreaks()
                                            ->bulleted()
                                            ->placeholder('Henüz çalışan eklenmemiş'),
                                    ])
                                    ->columns(2),
                            ]),
                        
                        TabsTab::make('Sistem Bilgileri')
                            ->schema([
                                Section::make('Oluşturulma Bilgileri')
                                    ->schema([
                                        TextEntry::make('created_at')
                                            ->label('Oluşturulma Tarihi')
                                            ->dateTime('d/m/Y H:i')
                                            ->icon('heroicon-o-calendar'),
                                        TextEntry::make('updated_at')
                                            ->label('Son Güncelleme')
                                            ->dateTime('d/m/Y H:i')
                                            ->icon('heroicon-o-clock'),
                                    ])
                                    ->columns(2),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
