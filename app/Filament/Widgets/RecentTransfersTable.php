<?php

namespace App\Filament\Widgets;

use App\Models\Transfer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentTransfersTable extends TableWidget
{
    protected static ?string $heading = 'Son Transferler';
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Transfer::query()
                    ->with(['fromUser', 'toUser'])
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('transfer_number')
                    ->label('Transfer No')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                TextColumn::make('fromUser.name')
                    ->label('Gönderen')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('toUser.name')
                    ->label('Alan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status_display')
                    ->label('Durum')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'beklemede' => 'warning',
                        'yolda' => 'info',
                        'teslim_edildi' => 'success',
                        'iptal' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('Oluşturulma Tarihi')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->paginated(false);
    }
}
