<?php

namespace App\Filament\Resources\Products\RelationManagers;

use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ImportAction;
use Filament\Actions\ExportBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use App\Filament\Imports\ProductCodeImporter;
use App\Filament\Exports\ProductCodeExporter;

class ProductCodesRelationManager extends RelationManager
{
    protected static string $relationship = 'productCodes';
    protected static ?string $title = 'Ürün Kodları';
    protected static ?string $recordTitleAttribute = 'code';
    protected static ?string $pluralRecordTitleAttribute = 'Ürün Kodları';
    protected static ?string $modelLabel = 'Ürün Kodu';
    protected static ?string $pluralModelLabel = 'Ürün Kodları';
    

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextInput::make('code')
                    ->label('Kod')
                    ->maxLength(255)
                    ->helperText('Ürün kodu (otomatik oluşturulur)'),

                Select::make('user_id')
                    ->label('Kullanıcı')
                    ->options(User::pluck('name', 'id'))
                    ->required()
                    ->searchable()
                    ->preload()
                    ->default(auth()->id())
                    ->helperText('Kod sahibi kullanıcı'),

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

                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true)
                    ->helperText('Kod aktif mi?'),

                Textarea::make('notes')
                    ->label('Notlar')
                    ->rows(3)
                    ->helperText('Ek notlar'),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('code')
            ->columns([
                TextColumn::make('code')
                    ->label('Kod')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable()
                    ->tooltip('Kopyalamak için tıklayın'),

                TextColumn::make('user.name')
                    ->label('Kullanıcı')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('location_display')
                    ->label('Lokasyon')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('warning'),

                TextColumn::make('formatted_quantity')
                    ->label('Miktar')
                    ->sortable(),

                TextColumn::make('formatted_remaining_quantity')
                    ->label('Kalan')
                    ->sortable()
                    ->color('success'),

                TextColumn::make('used_quantity')
                    ->label('Kullanılan')
                    ->formatStateUsing(fn($state, $record) => number_format($state, 2) . ' ' . $record->quantity_type)
                    ->sortable()
                    ->color('danger'),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                TextColumn::make('created_at')
                    ->label('Oluşturulma Tarihi')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('location_type')
                    ->label('Lokasyon Tipi')
                    ->options([
                        'merkez' => 'Merkez',
                        'bayi' => 'Bayi',
                        'depo' => 'Depo',
                        'kargo' => 'Kargo',
                        'musteri' => 'Müşteri',
                    ]),

                SelectFilter::make('quantity_type')
                    ->label('Miktar Tipi')
                    ->options([
                        'm2' => 'M²',
                        'kutu' => 'Kutu',
                        'adet' => 'Adet',
                        'kg' => 'Kg',
                        'lt' => 'Lt',
                    ]),

                TernaryFilter::make('is_active')
                    ->label('Aktif Durumu')
                    ->boolean()
                    ->trueLabel('Aktif')
                    ->falseLabel('Pasif')
                    ->native(false),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Yeni Kod Ekle'),
                
                ImportAction::make()
                    ->importer(ProductCodeImporter::class)
                    ->label('İçe Aktar')
                    ->color('success'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    ExportBulkAction::make()
                        ->exporter(ProductCodeExporter::class)
                        ->label('Dışa Aktar')
                        ->color('info'),
                    
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
