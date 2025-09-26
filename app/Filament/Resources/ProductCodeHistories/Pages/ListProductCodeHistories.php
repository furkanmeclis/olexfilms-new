<?php

namespace App\Filament\Resources\ProductCodeHistories\Pages;

use App\Filament\Resources\ProductCodeHistories\ProductCodeHistoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProductCodeHistories extends ListRecords
{
    protected static string $resource = ProductCodeHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
