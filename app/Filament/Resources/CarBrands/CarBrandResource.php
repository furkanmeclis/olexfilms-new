<?php

namespace App\Filament\Resources\CarBrands;

use App\Filament\Resources\CarBrands\Pages\CreateCarBrand;
use App\Filament\Resources\CarBrands\Pages\EditCarBrand;
use App\Filament\Resources\CarBrands\Pages\ListCarBrands;
use App\Filament\Resources\CarBrands\Pages\ViewCarBrand;
use App\Filament\Resources\CarBrands\RelationManagers\ModelsRelationManager;
use App\Filament\Resources\CarBrands\Schemas\CarBrandForm;
use App\Filament\Resources\CarBrands\Tables\CarBrandsTable;
use App\Models\CarBrand;
use BackedEnum;
use Filament\Resources\Resource;
use UnitEnum;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CarBrandResource extends Resource
{
    protected static ?string $model = CarBrand::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingStorefront;
    protected static ?string $recordTitleAttribute = 'name';
    protected static string|UnitEnum|null $navigationGroup = 'Araç Yönetimi';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Araç Markaları';
    protected static ?string $modelLabel = 'Araç Markası';
    protected static ?string $pluralModelLabel = 'Araç Markaları';

    public static function form(Schema $schema): Schema
    {
        return CarBrandForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CarBrandsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ModelsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCarBrands::route('/'),
            'create' => CreateCarBrand::route('/create'),
            'view' => ViewCarBrand::route('/{record}'),
            'edit' => EditCarBrand::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
