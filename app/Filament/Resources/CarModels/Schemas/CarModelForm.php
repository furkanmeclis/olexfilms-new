<?php

namespace App\Filament\Resources\CarModels\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class CarModelForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Araç Modeli Bilgileri')
                    ->tabs([
                        Tabs\Tab::make('Temel Bilgiler')
                            ->schema([
                                Section::make('Model Bilgileri')
                                    ->schema([
                                        Select::make('brand_id')
                                            ->label('Marka')
                                            ->relationship('brand', 'name')
                                            ->required()
                                            ->searchable()
                                            ->preload()
                                            ->helperText('Modelin ait olduğu markayı seçiniz'),
                                        
                                        TextInput::make('name')
                                            ->label('Model Adı')
                                            ->required()
                                            ->maxLength(255)
                                            ->helperText('Araç modelinin adını giriniz'),
                                        
                                        TextInput::make('external_id')
                                            ->label('Dış ID')
                                            ->maxLength(255)
                                            ->helperText('Harici sistem ID\'si (otomatik oluşturulur)'),
                                    ])
                                    ->columns(2),
                                
                                Section::make('Durum Bilgileri')
                                    ->schema([
                                        Toggle::make('is_active')
                                            ->label('Aktif')
                                            ->default(true)
                                            ->helperText('Model aktif mi?'),
                                        
                                        DateTimePicker::make('last_update')
                                            ->label('Son Güncelleme')
                                            ->helperText('Son güncelleme tarihi'),
                                    ])
                                    ->columns(2),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
