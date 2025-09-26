<?php

namespace App\Filament\Widgets;

use App\Models\ProductCode;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentProductCodesTable extends TableWidget
{
    protected static ?string $heading = 'Son Ürün Kodları';
    protected static ?int $sort = 5;
    protected int | string | array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ProductCode::query()
                    ->with(['product', 'user'])
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('code')
                    ->label('Ürün Kodu')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                TextColumn::make('product.name')
                    ->label('Ürün Adı')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                TextColumn::make('user.name')
                    ->label('Kullanıcı')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('location_display')
                    ->label('Konum')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('is_active')
                    ->label('Aktif')
                    ->badge()
                    ->color(fn (bool $state): string => $state ? 'success' : 'danger')
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Aktif' : 'Pasif'),

                TextColumn::make('created_at')
                    ->label('Oluşturulma Tarihi')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->paginated(false);
    }
}
