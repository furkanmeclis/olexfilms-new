<?php

namespace App\Filament\Resources\ProductCodes\Schemas;

use App\Models\Product;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class ProductCodeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Ürün Kodu Bilgileri')
                    ->tabs([
                        Tabs\Tab::make('Temel Bilgiler')
                            ->schema([
                                Section::make('Genel Bilgiler')
                                    ->schema([
                                        TextInput::make('code')
                                            ->label('Kod')
                                            ->maxLength(255)
                                            ->helperText('Ürün kodu (otomatik oluşturulur)'),
                                        
                                        Select::make('product_id')
                                            ->label('Ürün')
                                            ->relationship('product', 'name')
                                            ->required()
                                            ->searchable()
                                            ->preload()
                                            ->helperText('Ürün seçiniz'),
                                        
                                        Select::make('user_id')
                                            ->label('Kullanıcı')
                                            ->relationship('user', 'name')
                                            ->required()
                                            ->searchable()
                                            ->preload()
                                            ->default(auth()->id())
                                            ->helperText('Kod sahibi kullanıcı'),
                                    ])
                                    ->columns(2),
                                
                                Section::make('Lokasyon Bilgileri')
                                    ->schema([
                                        Select::make('location_type')
                                            ->label('Lokasyon Tipi')
                                            ->options([
                                                'merkez' => 'Merkez',
                                                'bayi' => 'Bayi',
                                                'depo' => 'Depo',
                                                'kargo' => 'Kargo',
                                                'musteri' => 'Müşteri',
                                            ])
                                            ->required()
                                            ->helperText('Kodun bulunduğu lokasyon tipi'),
                                        
                                        TextInput::make('location_name')
                                            ->label('Lokasyon Adı')
                                            ->maxLength(255)
                                            ->helperText('Lokasyon detay adı (opsiyonel)'),
                                    ])
                                    ->columns(2),
                            ]),
                        
                        Tabs\Tab::make('Miktar Bilgileri')
                            ->schema([
                                Section::make('Miktar Ayarları')
                                    ->schema([
                                        TextInput::make('quantity')
                                            ->label('Miktar')
                                            ->numeric()
                                            ->required()
                                            ->helperText('Toplam miktar'),
                                        
                                        Select::make('quantity_type')
                                            ->label('Miktar Tipi')
                                            ->options([
                                                'm2' => 'M²',
                                                'kutu' => 'Kutu',
                                                'adet' => 'Adet',
                                                'kg' => 'Kg',
                                                'lt' => 'Lt',
                                            ])
                                            ->required()
                                            ->helperText('Miktar birimi'),
                                    ])
                                    ->columns(2),
                            ]),
                        
                        Tabs\Tab::make('Ayarlar')
                            ->schema([
                                Section::make('Durum ve Notlar')
                                    ->schema([
                                        Toggle::make('is_active')
                                            ->label('Aktif')
                                            ->default(true)
                                            ->helperText('Kod aktif mi?'),
                                        
                                        Textarea::make('notes')
                                            ->label('Notlar')
                                            ->rows(4)
                                            ->helperText('Ek notlar'),
                                    ])
                                    ->columns(1),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
