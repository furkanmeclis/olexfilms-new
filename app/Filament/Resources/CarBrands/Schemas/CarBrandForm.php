<?php

namespace App\Filament\Resources\CarBrands\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class CarBrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Araç Markası Bilgileri')
                    ->tabs([
                        Tabs\Tab::make('Temel Bilgiler')
                            ->schema([
                                Section::make('Genel Bilgiler')
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Marka Adı')
                                            ->required()
                                            ->maxLength(255)
                                            ->helperText('Araç markasının adını giriniz'),
                                        
                                        TextInput::make('external_id')
                                            ->label('Dış ID')
                                            ->maxLength(255)
                                            ->helperText('Harici sistem ID\'si (otomatik oluşturulur)'),
                                    ])
                                    ->columns(2),
                                
                                Section::make('Logo ve Durum')
                                    ->schema([
                                        FileUpload::make('logo')
                                            ->label('Marka Logosu')
                                            ->image()
                                            ->directory('car-brands')
                                            ->imagePreviewHeight('150')
                                            ->imageEditor()
                                            ->imageEditorAspectRatios([
                                                '16:9',
                                                '4:3',
                                                '1:1',
                                            ])
                                            ->helperText('Marka logosunu yükleyiniz'),
                                        
                                        Toggle::make('is_active')
                                            ->label('Aktif')
                                            ->default(true)
                                            ->helperText('Marka aktif mi?'),
                                    ])
                                    ->columns(2),
                            ]),
                        
                        Tabs\Tab::make('Güncelleme Bilgileri')
                            ->schema([
                                Section::make('Son Güncelleme')
                                    ->schema([
                                        DateTimePicker::make('last_update')
                                            ->label('Son Güncelleme')
                                            ->helperText('Son güncelleme tarihi'),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
