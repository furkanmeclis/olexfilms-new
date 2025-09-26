<?php

namespace App\Filament\Resources\ProductCodeHistories;

use App\Filament\Resources\ProductCodeHistories\Pages\CreateProductCodeHistory;
use App\Filament\Resources\ProductCodeHistories\Pages\EditProductCodeHistory;
use App\Filament\Resources\ProductCodeHistories\Pages\ListProductCodeHistories;
use App\Filament\Resources\ProductCodeHistories\Schemas\ProductCodeHistoryForm;
use App\Filament\Resources\ProductCodeHistories\Tables\ProductCodeHistoriesTable;
use App\Models\ProductCodeHistory;
use BackedEnum;
use Filament\Resources\Resource;
use UnitEnum;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProductCodeHistoryResource extends Resource
{
    protected static ?string $model = ProductCodeHistory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;
    protected static ?string $recordTitleAttribute = 'action_display';
    protected static string|UnitEnum|null $navigationGroup = 'Transfer Yönetimi';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'İşlem Geçmişi';
    protected static ?string $modelLabel = 'İşlem Kaydı';
    protected static ?string $pluralModelLabel = 'İşlem Geçmişi';
    protected static ?bool $shouldSplitGlobalSearchTerms = false;

    public static function form(Schema $schema): Schema
    {
        return ProductCodeHistoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductCodeHistoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProductCodeHistories::route('/'),
        ];
    }
}
