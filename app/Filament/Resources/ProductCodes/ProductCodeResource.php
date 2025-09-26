<?php

namespace App\Filament\Resources\ProductCodes;

use App\Filament\Resources\ProductCodes\Pages\CreateProductCode;
use App\Filament\Resources\ProductCodes\Pages\EditProductCode;
use App\Filament\Resources\ProductCodes\Pages\ListProductCodes;
use App\Filament\Resources\ProductCodes\Pages\ViewProductCode;
use App\Filament\Resources\ProductCodes\Schemas\ProductCodeForm;
use App\Filament\Resources\ProductCodes\Tables\ProductCodesTable;
use App\Models\ProductCode;
use BackedEnum;
use Filament\Resources\Resource;
use UnitEnum;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProductCodeResource extends Resource
{
    protected static ?string $model = ProductCode::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQrCode;

    protected static ?string $recordTitleAttribute = 'code';
    protected static string|UnitEnum|null $navigationGroup = 'Stok Yönetimi';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Ürün Kodları';
    protected static ?string $modelLabel = 'Ürün Kodu';
    protected static ?string $pluralModelLabel = 'Ürün Kodları';

    public static function form(Schema $schema): Schema
    {
        return ProductCodeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductCodesTable::configure($table);
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
            'index' => ListProductCodes::route('/'),
            'create' => CreateProductCode::route('/create'),
            'view' => ViewProductCode::route('/{record}'),
            'edit' => EditProductCode::route('/{record}/edit'),
        ];
    }
}
