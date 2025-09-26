<?php

namespace App\Filament\Resources\Dealers\Tables;

use App\Filament\Exports\DealerExporter;
use App\Filament\Imports\DealerImporter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportBulkAction;
use Filament\Actions\ImportAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class DealersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo_path')
                    ->label('Logo')
                    ->circular()
                    ->size(40),
                TextColumn::make('company_name')
                    ->label('Şirket Adı')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('user.name')
                    ->label('Kullanıcı')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('city')
                    ->label('Şehir')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('phone')
                    ->label('Telefon')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('email')
                    ->label('E-posta')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('workers_count')
                    ->label('Çalışan Sayısı')
                    ->counts('workers')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                IconColumn::make('is_verified')
                    ->label('Doğrulanmış')
                    ->boolean()
                    ->trueIcon('heroicon-o-shield-check')
                    ->falseIcon('heroicon-o-shield-exclamation')
                    ->trueColor('success')
                    ->falseColor('warning'),
                TextColumn::make('verified_at')
                    ->label('Doğrulama Tarihi')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('city')
                    ->label('Şehir')
                    ->options(function () {
                        return \App\Models\Dealer::distinct()
                            ->pluck('city', 'city')
                            ->filter()
                            ->toArray();
                    })
                    ->searchable(),
                TernaryFilter::make('is_active')
                    ->label('Aktif Durumu')
                    ->boolean()
                    ->trueLabel('Aktif')
                    ->falseLabel('Pasif')
                    ->native(false),
                TernaryFilter::make('is_verified')
                    ->label('Doğrulama Durumu')
                    ->boolean()
                    ->trueLabel('Doğrulanmış')
                    ->falseLabel('Doğrulanmamış')
                    ->native(false),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                ImportAction::make()
                    ->importer(DealerImporter::class)
                    ->label('Import')
                    ->color('success'),
                ExportBulkAction::make()
                    ->exporter(DealerExporter::class)
                    ->label('Export')
                    ->color('info'),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
