<?php

namespace App\Filament\Resources\ProductCodes\Pages;

use App\Filament\Resources\ProductCodes\ProductCodeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProductCode extends CreateRecord
{
    protected static string $resource = ProductCodeResource::class;
}
