<?php

namespace App\Filament\Resources\ProductCodeHistories\Pages;

use App\Filament\Resources\ProductCodeHistories\ProductCodeHistoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProductCodeHistory extends EditRecord
{
    protected static string $resource = ProductCodeHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
