<?php

namespace App\Filament\Resources\ProductCodes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ImportAction;
use Filament\Actions\ExportBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Imports\ProductCodeImporter;
use App\Filament\Exports\ProductCodeExporter;

class ProductCodesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Kod')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable()
                    ->tooltip('Kopyalamak için tıklayın'),
                
                TextColumn::make('product.name')
                    ->label('Ürün')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),
                
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
                    ->formatStateUsing(fn ($state, $record) => number_format($state, 2) . ' ' . $record->quantity_type)
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
                SelectFilter::make('product_id')
                    ->label('Ürün')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->preload(),
                
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
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                ImportAction::make()
                    ->importer(ProductCodeImporter::class)
                    ->label('İçe Aktar')
                    ->color('success'),
                
                BulkActionGroup::make([
                    ExportBulkAction::make()
                        ->exporter(ProductCodeExporter::class)
                        ->label('Dışa Aktar')
                        ->color('info'),
                    
                    \Filament\Actions\BulkAction::make('transfer')
                        ->label('Transfer Et')
                        ->icon('heroicon-o-truck')
                        ->color('warning')
                        ->form([
                            \Filament\Forms\Components\Select::make('to_user_id')
                                ->label('Alıcı Kullanıcı')
                                ->options(\App\Models\User::pluck('name', 'id'))
                                ->required()
                                ->searchable()
                                ->preload()
                                ->helperText('Transferi alacak kullanıcıyı seçiniz'),
                            
                            \Filament\Forms\Components\TextInput::make('cargo_code')
                                ->label('Kargo Kodu')
                                ->maxLength(255)
                                ->helperText('Kargo takip numarası (opsiyonel)'),
                            
                            \Filament\Forms\Components\TextInput::make('cargo_company')
                                ->label('Kargo Şirketi')
                                ->maxLength(255)
                                ->helperText('Kargo şirketi adı (opsiyonel)'),
                            
                            \Filament\Forms\Components\Textarea::make('notes')
                                ->label('Transfer Notları')
                                ->rows(3)
                                ->helperText('Transfer hakkında notlar'),
                        ])
                        ->action(function (Collection $records, array $data) {
                            // Transfer oluştur
                            $transfer = \App\Models\Transfer::create([
                                'from_user_id' => auth()->id(),
                                'to_user_id' => $data['to_user_id'],
                                'cargo_code' => $data['cargo_code'] ?? null,
                                'cargo_company' => $data['cargo_company'] ?? null,
                                'notes' => $data['notes'] ?? null,
                                'created_by' => auth()->id(),
                            ]);
                            
                            // Transfer kalemlerini oluştur
                            foreach ($records as $productCode) {
                                \App\Models\TransferItem::create([
                                    'transfer_id' => $transfer->id,
                                    'product_code_id' => $productCode->id,
                                    'quantity' => $productCode->remaining_quantity,
                                    'quantity_type' => $productCode->quantity_type,
                                ]);
                                
                                // Ürün kodunu transfer durumuna güncelle
                                $productCode->transferTo('kargo', $data['cargo_company'] ?? 'Transfer', $data['notes'] ?? null);
                            }
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Transfer Oluşturuldu')
                                ->success()
                                ->body("{$records->count()} ürün kodu transfer edildi. Transfer No: {$transfer->transfer_number}")
                                ->send();
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Toplu Transfer')
                        ->modalDescription('Seçili ürün kodlarını transfer etmek istediğinizden emin misiniz?')
                        ->modalSubmitActionLabel('Transfer Et'),
                    
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
