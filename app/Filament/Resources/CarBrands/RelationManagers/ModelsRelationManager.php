<?php

namespace App\Filament\Resources\CarBrands\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ImportAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use App\Filament\Imports\CarModelImporter;
use App\Filament\Exports\CarModelExporter;
use Filament\Actions\ExportBulkAction;

class ModelsRelationManager extends RelationManager
{
    protected static string $relationship = 'models';
    
    protected static ?string $title = 'Araç Modelleri';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Model Adı')
                    ->required()
                    ->maxLength(255)
                    ->helperText('Araç modelinin adını giriniz'),
                
                TextInput::make('external_id')
                    ->label('Dış ID')
                    ->maxLength(255)
                    ->helperText('Harici sistem ID\'si (otomatik oluşturulur)'),
                
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true)
                    ->helperText('Model aktif mi?'),
                
                DateTimePicker::make('last_update')
                    ->label('Son Güncelleme')
                    ->helperText('Son güncelleme tarihi'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
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
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Aktif Durumu')
                    ->boolean()
                    ->trueLabel('Aktif')
                    ->falseLabel('Pasif')
                    ->native(false),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Yeni Model'),
                AssociateAction::make()
                    ->label('Mevcut Model Ekle'),
                ImportAction::make()
                    ->importer(CarModelImporter::class)
                    ->label('Import')
                    ->color('success'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DissociateAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    ExportBulkAction::make()
                        ->exporter(CarModelExporter::class)
                        ->label('Export')
                        ->color('info'),
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name', 'asc');
    }
}
