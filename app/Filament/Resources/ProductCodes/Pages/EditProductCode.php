<?php

namespace App\Filament\Resources\ProductCodes\Pages;

use App\Filament\Resources\ProductCodes\ProductCodeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProductCode extends EditRecord
{
    protected static string $resource = ProductCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
