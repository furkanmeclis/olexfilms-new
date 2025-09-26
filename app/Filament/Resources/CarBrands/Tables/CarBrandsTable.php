<?php

namespace App\Filament\Resources\CarBrands\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\ImportAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use App\Filament\Imports\CarBrandImporter;
use App\Filament\Exports\CarBrandExporter;
use Filament\Actions\ExportBulkAction;

class CarBrandsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')
                    ->label('Logo')
                    ->circular()
                    ->size(40)
                    ->defaultImageUrl('/images/placeholder-brand.png'),
                
                TextColumn::make('name')
                    ->label('Marka Adı')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                TextColumn::make('external_id')
                    ->label('Dış ID')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->tooltip('Kopyalamak için tıklayın'),
                
                TextColumn::make('models_count')
                    ->label('Model Sayısı')
                    ->counts('models')
                    ->badge()
                    ->color('info'),
                
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
                    ->importer(CarBrandImporter::class)
                    ->label('Import')
                    ->color('success'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    ExportBulkAction::make()
                        ->exporter(CarBrandExporter::class)
                        ->label('Export')
                        ->color('info'),
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('name', 'asc');
    }
}
