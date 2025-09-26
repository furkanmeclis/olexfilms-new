<?php

namespace App\Filament\Resources\ProductCodes\Pages;

use App\Filament\Resources\ProductCodes\ProductCodeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProductCodes extends ListRecords
{
    protected static string $resource = ProductCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
