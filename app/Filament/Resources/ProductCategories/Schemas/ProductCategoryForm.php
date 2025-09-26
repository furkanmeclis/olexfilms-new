<?php

namespace App\Filament\Resources\ProductCategories\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class ProductCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Kategori Bilgileri')
                    ->tabs([
                        Tabs\Tab::make('Temel Bilgiler')
                            ->schema([
                                \Filament\Schemas\Components\Section::make('Genel Bilgiler')
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Kategori Adı')
                                            ->required()
                                            ->maxLength(255)
                                            ->helperText('Kategori adını giriniz'),
                                        
                                        TextInput::make('slug')
                                            ->label('URL Slug')
                                            ->maxLength(255)
                                            ->helperText('URL için kullanılacak slug (otomatik oluşturulur)'),
                                        
                                        Textarea::make('description')
                                            ->label('Açıklama')
                                            ->rows(4)
                                            ->helperText('Kategori açıklamasını giriniz'),
                                        
                                        FileUpload::make('image_path')
                                            ->label('Kategori Resmi')
                                            ->image()
                                            ->directory('product-categories')
                                            ->helperText('Kategori için resim yükleyiniz'),
                                    ])
                                    ->columns(2),
                            ]),
                        
                        Tabs\Tab::make('Ayarlar')
                            ->schema([
                                \Filament\Schemas\Components\Section::make('Durum ve Sıralama')
                                    ->schema([
                                        Toggle::make('is_active')
                                            ->label('Aktif')
                                            ->default(true)
                                            ->helperText('Kategori aktif mi?'),
                                        
                                        TextInput::make('sort_order')
                                            ->label('Sıralama')
                                            ->numeric()
                                            ->default(0)
                                            ->helperText('Kategori sıralama numarası'),
                                    ])
                                    ->columns(2),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
