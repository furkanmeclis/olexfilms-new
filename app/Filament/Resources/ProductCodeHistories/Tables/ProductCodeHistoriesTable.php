<?php

namespace App\Filament\Resources\ProductCodeHistories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProductCodeHistoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('productCode.code')
                    ->label('Ürün Kodu')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable()
                    ->tooltip('Kopyalamak için tıklayın'),
                
                TextColumn::make('productCode.product.name')
                    ->label('Ürün')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),
                
                TextColumn::make('action_display')
                    ->label('İşlem')
                    ->badge()
                    ->color(fn ($record) => match($record->action_type) {
                        'created' => 'success',
                        'updated' => 'info',
                        'used' => 'warning',
                        'transferred' => 'primary',
                        'received' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
                
                TextColumn::make('user.name')
                    ->label('Kullanıcı')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('quantity_before')
                    ->label('Önceki Miktar')
                    ->formatStateUsing(fn ($state) => number_format((float) $state, 2))
                    ->sortable(),
                
                TextColumn::make('quantity_after')
                    ->label('Sonraki Miktar')
                    ->formatStateUsing(fn ($state) => number_format((float) $state, 2))
                    ->sortable(),
                
                TextColumn::make('quantity_change')
                    ->label('Değişim')
                    ->formatStateUsing(fn ($state) => $state > 0 ? '+' . number_format((float) $state, 2) : number_format((float) $state, 2))
                    ->color(fn ($state) => $state > 0 ? 'success' : 'danger')
                    ->sortable(),
                
                TextColumn::make('location_display_before')
                    ->label('Önceki Lokasyon')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('warning'),
                
                TextColumn::make('location_display_after')
                    ->label('Sonraki Lokasyon')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('success'),
                
                TextColumn::make('notes')
                    ->label('Notlar')
                    ->limit(50)
                    ->tooltip(fn (TextColumn $column): ?string => strlen($column->getState()) <= 50 ? null : $column->getState())
                    ->toggleable(),
                
                TextColumn::make('created_at')
                    ->label('Tarih')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->weight('bold'),
            ])
            ->filters([
                SelectFilter::make('action_type')
                    ->label('İşlem Tipi')
                    ->options([
                        'created' => 'Oluşturuldu',
                        'updated' => 'Güncellendi',
                        'used' => 'Kullanıldı',
                        'transferred' => 'Transfer Edildi',
                        'received' => 'Alındı',
                        'cancelled' => 'İptal Edildi',
                    ]),
                
                SelectFilter::make('user_id')
                    ->label('Kullanıcı')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
                
                SelectFilter::make('product_code_id')
                    ->label('Ürün Kodu')
                    ->relationship('productCode', 'code')
                    ->searchable()
                    ->preload(),
            ])
            ->toolbarActions([

                BulkActionGroup::make([
                   
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
