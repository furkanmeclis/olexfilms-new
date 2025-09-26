<?php

namespace App\Filament\Resources\Dealers\Schemas;

use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class DealerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Bayi Profili')
                    ->tabs([
                        Tabs\Tab::make('Temel Bilgiler')
                            ->schema([
                                Section::make('Kullanıcı Bilgileri')
                                    ->schema([
                                        Select::make('user_id')
                                            ->label('Kullanıcı')
                                            ->relationship('user', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->helperText('Bu bayi profili hangi kullanıcıya ait olacak?'),
                                    ])
                                    ->columns(1),
                                
                                Section::make('Şirket Bilgileri')
                                    ->schema([
                                        TextInput::make('company_name')
                                            ->label('Şirket Adı')
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('trade_name')
                                            ->label('Ticari Unvan')
                                            ->maxLength(255),
                                        TextInput::make('tax_number')
                                            ->label('Vergi Numarası')
                                            ->maxLength(20),
                                        TextInput::make('tax_office')
                                            ->label('Vergi Dairesi')
                                            ->maxLength(255),
                                        TextInput::make('established_year')
                                            ->label('Kuruluş Yılı')
                                            ->numeric()
                                            ->minValue(1900)
                                            ->maxValue(date('Y')),
                                    ])
                                    ->columns(2),
                                
                                Section::make('Görsel İçerik')
                                    ->schema([
                                        FileUpload::make('logo_path')
                                            ->label('Logo')
                                            ->image()
                                            ->imageEditor()
                                            ->imageEditorAspectRatios([
                                                '1:1',
                                                '16:9',
                                                '4:3',
                                            ])
                                            ->maxSize(2048)
                                            ->directory('dealers/logos'),
                                        FileUpload::make('cover_image_path')
                                            ->label('Kapak Resmi')
                                            ->image()
                                            ->imageEditor()
                                            ->imageEditorAspectRatios([
                                                '16:9',
                                                '21:9',
                                                '4:3',
                                            ])
                                            ->maxSize(5120)
                                            ->directory('dealers/covers'),
                                    ])
                                    ->columns(2),
                            ]),
                        
                        Tabs\Tab::make('İletişim Bilgileri')
                            ->schema([
                                Section::make('İletişim')
                                    ->schema([
                                        TextInput::make('phone')
                                            ->label('Telefon')
                                            ->tel()
                                            ->maxLength(20),
                                        TextInput::make('mobile')
                                            ->label('Cep Telefonu')
                                            ->tel()
                                            ->maxLength(20),
                                        TextInput::make('email')
                                            ->label('E-posta')
                                            ->email()
                                            ->maxLength(255),
                                        TextInput::make('website')
                                            ->label('Web Sitesi')
                                            ->url()
                                            ->maxLength(255),
                                    ])
                                    ->columns(2),
                                
                                Section::make('Adres Bilgileri')
                                    ->schema([
                                        Textarea::make('address')
                                            ->label('Adres')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                        TextInput::make('city')
                                            ->label('Şehir')
                                            ->maxLength(100),
                                        TextInput::make('district')
                                            ->label('İlçe')
                                            ->maxLength(100),
                                        TextInput::make('postal_code')
                                            ->label('Posta Kodu')
                                            ->maxLength(10),
                                        TextInput::make('country')
                                            ->label('Ülke')
                                            ->default('Türkiye')
                                            ->maxLength(100),
                                    ])
                                    ->columns(2),
                                
                                Section::make('Konum Bilgileri')
                                    ->schema([
                                        TextInput::make('latitude')
                                            ->label('Enlem')
                                            ->numeric()
                                            ->step(0.0000001)
                                            ->helperText('Google Maps\'ten alabilirsiniz'),
                                        TextInput::make('longitude')
                                            ->label('Boylam')
                                            ->numeric()
                                            ->step(0.0000001)
                                            ->helperText('Google Maps\'ten alabilirsiniz'),
                                        TextInput::make('location_name')
                                            ->label('Konum Adı')
                                            ->maxLength(255)
                                            ->helperText('Örn: Merkez Ofis, Şube 1'),
                                    ])
                                    ->columns(2),
                            ]),
                        
                        Tabs\Tab::make('Sosyal Medya')
                            ->schema([
                                Section::make('Sosyal Medya Hesapları')
                                    ->schema([
                                        TextInput::make('facebook_url')
                                            ->label('Facebook')
                                            ->url()
                                            ->prefix('https://facebook.com/')
                                            ->maxLength(255),
                                        TextInput::make('instagram_url')
                                            ->label('Instagram')
                                            ->url()
                                            ->prefix('https://instagram.com/')
                                            ->maxLength(255),
                                        TextInput::make('twitter_url')
                                            ->label('Twitter')
                                            ->url()
                                            ->prefix('https://twitter.com/')
                                            ->maxLength(255),
                                        TextInput::make('linkedin_url')
                                            ->label('LinkedIn')
                                            ->url()
                                            ->prefix('https://linkedin.com/')
                                            ->maxLength(255),
                                        TextInput::make('youtube_url')
                                            ->label('YouTube')
                                            ->url()
                                            ->prefix('https://youtube.com/')
                                            ->maxLength(255),
                                        TextInput::make('tiktok_url')
                                            ->label('TikTok')
                                            ->url()
                                            ->prefix('https://tiktok.com/')
                                            ->maxLength(255),
                                    ])
                                    ->columns(2),
                            ]),
                        
                        Tabs\Tab::make('İş Bilgileri')
                            ->schema([
                                Section::make('Açıklamalar')
                                    ->schema([
                                        Textarea::make('description')
                                            ->label('Bayi Açıklaması')
                                            ->rows(4)
                                            ->columnSpanFull()
                                            ->helperText('Bayi hakkında detaylı açıklama'),
                                        Textarea::make('services')
                                            ->label('Hizmetler')
                                            ->rows(4)
                                            ->columnSpanFull()
                                            ->helperText('Sunulan hizmetler listesi'),
                                        Textarea::make('working_hours')
                                            ->label('Çalışma Saatleri')
                                            ->rows(3)
                                            ->columnSpanFull()
                                            ->helperText('Örn: Pazartesi-Cuma: 09:00-18:00'),
                                    ]),
                                
                                Section::make('Durum')
                                    ->schema([
                                        Toggle::make('is_active')
                                            ->label('Aktif')
                                            ->default(true)
                                            ->helperText('Bayi aktif mi?'),
                                        Toggle::make('is_verified')
                                            ->label('Doğrulanmış')
                                            ->default(false)
                                            ->helperText('Bayi doğrulanmış mı?'),
                                        DateTimePicker::make('verified_at')
                                            ->label('Doğrulama Tarihi')
                                            ->displayFormat('d/m/Y H:i')
                                            ->visible(fn ($get) => $get('is_verified')),
                                    ])
                                    ->columns(2),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
