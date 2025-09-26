<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Filament\Resources\ProductCategories\Schemas\ProductCategoryForm;
use App\Models\ProductCategory;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Ürün Bilgileri')
                    ->tabs([
                        Tabs\Tab::make('Temel Bilgiler')
                            ->schema([
                                Section::make('Genel Bilgiler')
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Ürün Adı')
                                            ->required()
                                            ->maxLength(255)
                                            ->helperText('Ürün adını giriniz'),
                                        
                                        TextInput::make('slug')
                                            ->label('URL Slug')
                                            ->maxLength(255)
                                            ->helperText('URL için kullanılacak slug (otomatik oluşturulur)'),
                                        
                                        TextInput::make('sku')
                                            ->label('SKU')
                                            ->maxLength(255)
                                            ->helperText('Ürün SKU kodu (otomatik oluşturulur)'),
                                        
                                        Select::make('product_category_id')
                                            ->label('Kategori')
                                            ->relationship('category', 'name')
                                            ->required()
                                            ->searchable()
                                            ->preload()
                                            ->createOptionModalHeading('Yeni Kategori Oluştur')
                                            ->createOptionForm([
                                                TextInput::make('name')
                                                    ->label('Kategori Adı')
                                                    ->required()
                                                    ->maxLength(255),
                                            ])
                                            ->helperText('Ürün kategorisini seçiniz'),
                                    ])
                                    ->columns(2),
                                
                                Section::make('Açıklamalar')
                                    ->schema([
                                        RichEditor::make('description')
                                            ->label('Ürün Açıklaması')
                                            ->helperText('Ürün açıklamasını giriniz'),
                                        
                                        RichEditor::make('specifications')
                                            ->label('Teknik Özellikler')
                                            ->helperText('Ürün teknik özelliklerini giriniz'),
                                    ])
                                    ->columns(1),
                            ]),
                        
                        Tabs\Tab::make('Fiyat ve Garanti')
                            ->schema([
                                Section::make('Fiyat Bilgileri')
                                    ->schema([
                                        TextInput::make('price')
                                            ->label('Fiyat (₺)')
                                            ->numeric()
                                            ->required()
                                            ->prefix('₺')
                                            ->helperText('Ürün fiyatını giriniz'),
                                        
                                        TextInput::make('warranty_months')
                                            ->label('Garanti Süresi (Ay)')
                                            ->numeric()
                                            ->required()
                                            ->suffix('Ay')
                                            ->helperText('Garanti süresini ay olarak giriniz'),
                                    ])
                                    ->columns(2),
                            ]),
                        
                        Tabs\Tab::make('Ürün Resimleri')
                            ->schema([
                                Section::make('Resim Yönetimi')
                                    ->schema([
                                        Repeater::make('images')
                                            ->label('Ürün Resimleri')
                                            ->relationship('images')
                                            ->schema([
                                                FileUpload::make('image_path')
                                                    ->label('Resim')
                                                    ->image()
                                                    ->directory('products')
                                                    ->imagePreviewHeight('150')
                                                    ->imageEditor()
                                                    ->imageEditorAspectRatios([
                                                        '16:9',
                                                        '4:3',
                                                        '1:1',
                                                    ])
                                                    ->required(),
                                                
                                                TextInput::make('alt_text')
                                                    ->label('Alt Metin')
                                                    ->maxLength(255)
                                                    ->helperText('Resim için alt metin'),
                                                
                                                Toggle::make('is_primary')
                                                    ->label('Ana Resim')
                                                    ->helperText('Bu resim ana resim mi?'),
                                                
                                                TextInput::make('sort_order')
                                                    ->label('Sıralama')
                                                    ->numeric()
                                                    ->default(0)
                                                    ->helperText('Resim sıralama numarası'),
                                            ])
                                            ->columns(2)
                                            ->collapsible()
                                            ->itemLabel(fn (array $state): ?string => $state['alt_text'] ?? 'Resim')
                                            ->helperText('Ürün için resimler ekleyiniz'),
                                    ]),
                            ]),
                        
                        Tabs\Tab::make('Ayarlar')
                            ->schema([
                                Section::make('Durum ve Sıralama')
                                    ->schema([
                                        Toggle::make('is_active')
                                            ->label('Aktif')
                                            ->default(true)
                                            ->helperText('Ürün aktif mi?'),
                                        
                                        TextInput::make('sort_order')
                                            ->label('Sıralama')
                                            ->numeric()
                                            ->default(0)
                                            ->helperText('Ürün sıralama numarası'),
                                    ])
                                    ->columns(2),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
