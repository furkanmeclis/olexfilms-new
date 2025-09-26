<?php

namespace App\Filament\Resources\Transfers\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';
    protected static ?string $title = 'Transfer Kalemleri';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
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
                    ->rows(3)
                    ->helperText('Transfer kalemi hakkında notlar'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('formatted_quantity')
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
                
                TextColumn::make('formatted_quantity')
                    ->label('Miktar')
                    ->sortable(),
                
                TextColumn::make('productCode.location_display')
                    ->label('Mevcut Lokasyon')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('warning'),
                
                TextColumn::make('notes')
                    ->label('Notlar')
                    ->limit(50)
                    ->tooltip(fn (TextColumn $column): ?string => strlen($column->getState()) <= 50 ? null : $column->getState())
                    ->toggleable(),
                
                TextColumn::make('created_at')
                    ->label('Oluşturulma Tarihi')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
