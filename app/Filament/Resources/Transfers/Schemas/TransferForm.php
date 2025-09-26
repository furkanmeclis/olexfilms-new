<?php

namespace App\Filament\Resources\Transfers\Schemas;

use App\Models\User;
use App\Models\ProductCode;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class TransferForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Transfer Bilgileri')
                    ->tabs([
                        Tabs\Tab::make('Temel Bilgiler')
                            ->schema([
                                Section::make('Transfer Detayları')
                                    ->schema([
                                        TextInput::make('transfer_number')
                                            ->label('Transfer Numarası')
                                            ->maxLength(255)
                                            ->helperText('Transfer numarası (otomatik oluşturulur)'),
                                        
                                        Select::make('from_user_id')
                                            ->label('Gönderen')
                                            ->relationship('fromUser', 'name')
                                            ->required()
                                            ->searchable()
                                            ->preload()
                                            ->default(auth()->user()->id)
                                            ->disabled()
                                            ->helperText('Transferi gönderen kullanıcı'),
                                        
                                        Select::make('to_user_id')
                                            ->label('Alıcı')
                                            ->relationship('toUser', 'name', modifyQueryUsing: fn($query) => $query->whereHas('roles', function($q) {
                                                $q->where('name', 'dealer');
                                            }))
                                            ->required()
                                            ->searchable()
                                            ->preload()
                                            ->helperText('Transferi alacak kullanıcı'),
                                        
                                        Select::make('status')
                                            ->label('Durum')
                                            ->options([
                                                'beklemede' => 'Beklemede',
                                                'yolda' => 'Yolda',
                                                'teslim_edildi' => 'Teslim Edildi',
                                                'iptal' => 'İptal',
                                            ])
                                            ->default('beklemede')
                                            ->required()
                                            ->helperText('Transfer durumu'),
                                    ])
                                    ->columns(2),
                            ]),
                        
                        Tabs\Tab::make('Transfer Kalemleri')
                            ->schema([
                                Section::make('Ürün Kodları')
                                    ->schema([
                                        Repeater::make('items')
                                            ->label('Transfer Kalemleri')
                                            ->relationship('items')
                                            ->schema([
                                                Select::make('product_code_id')
                                                    ->label('Ürün Kodu')
                                                    ->relationship(
                                                        name: 'productCode',
                                                        titleAttribute: 'code',
                                                        modifyQueryUsing:  fn($query) => $query->where('is_active', true)
                                                    )
                                                    ->getOptionLabelFromRecordUsing(fn($record) => $record->code . ' - ' . ($record->product?->name ?? ''))
                                                    ->required()
                                                    ->searchable()
                                                    ->preload()
                                                    ->helperText('Transfer edilecek ürün kodunu seçiniz'),
                                                
                                                TextInput::make('quantity')
                                                    ->label('Miktar')
                                                    ->numeric()
                                                    ->required()
                                                    ->helperText('Transfer miktarı'),
                                                
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
                                                
                                                Textarea::make('notes')
                                                    ->label('Notlar')
                                                    ->rows(2)
                                                    ->helperText('Bu kalem hakkında notlar'),
                                            ])
                                            ->columns(2)
                                            ->collapsible()
                                            ->itemLabel(fn (array $state): ?string => 
                                                $state['product_code_id'] ? 
                                                ProductCode::find($state['product_code_id'])?->code . ' - ' . 
                                                ProductCode::find($state['product_code_id'])?->product?->name : 
                                                'Yeni Kalem'
                                            )
                                            ->helperText('Transfer edilecek ürün kodlarını ekleyiniz')
                                            ->defaultItems(1),
                                    ]),
                            ]),
                        
                        Tabs\Tab::make('Kargo Bilgileri')
                            ->schema([
                                Section::make('Kargo Detayları')
                                    ->schema([
                                        TextInput::make('cargo_code')
                                            ->label('Kargo Kodu')
                                            ->maxLength(255)
                                            ->helperText('Kargo takip numarası'),
                                        
                                        TextInput::make('cargo_company')
                                            ->label('Kargo Şirketi')
                                            ->maxLength(255)
                                            ->helperText('Kargo şirketi adı'),
                                    ])
                                    ->columns(2),
                            ]),
                        
                        Tabs\Tab::make('Notlar')
                            ->schema([
                                Section::make('Ek Bilgiler')
                                    ->schema([
                                        Textarea::make('notes')
                                            ->label('Notlar')
                                            ->rows(4)
                                            ->helperText('Transfer hakkında ek notlar'),
                                    ])
                                    ->columns(1),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
