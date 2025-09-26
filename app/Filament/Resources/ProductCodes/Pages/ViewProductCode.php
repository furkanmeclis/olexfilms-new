<?php

namespace App\Filament\Resources\ProductCodes\Pages;

use App\Filament\Resources\ProductCodes\ProductCodeResource;
use Filament\Actions\EditAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Resources\Pages\ViewRecord;

class ViewProductCode extends ViewRecord
{
    protected static string $resource = ProductCodeResource::class;


    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

}
