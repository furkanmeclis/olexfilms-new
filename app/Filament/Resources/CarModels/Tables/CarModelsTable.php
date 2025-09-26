<?php

namespace App\Filament\Resources\CarModels\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\ImportAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use App\Filament\Imports\CarModelImporter;
use App\Filament\Exports\CarModelExporter;
use Filament\Actions\ExportBulkAction;

class CarModelsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('brand.name')
                    ->label('Marka')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),
                
                TextColumn::make('name')
                    ->label('Model Adı')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                TextColumn::make('external_id')
                    ->label('Dış ID')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->tooltip('Kopyalamak için tıklayın'),
                
                TextColumn::make('last_update')
                    ->label('Son Güncelleme')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),
                
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                
                TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('updated_at')
                    ->label('Güncelleme')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('brand_id')
                    ->label('Marka')
                    ->relationship('brand', 'name')
                    ->searchable()
                    ->preload(),
                
                TernaryFilter::make('is_active')
                    ->label('Aktif Durumu')
                    ->boolean()
                    ->trueLabel('Aktif')
                    ->falseLabel('Pasif')
                    ->native(false),
                
                TrashedFilter::make(),
            ])
            ->headerActions([
                ImportAction::make()
                    ->importer(CarModelImporter::class)
                    ->label('İçe Aktar')
                    ->color('success'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    ExportBulkAction::make()
                        ->exporter(CarModelExporter::class)
                        ->label('Dışa Aktar')
                        ->color('info'),
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('name', 'asc');
    }
}
