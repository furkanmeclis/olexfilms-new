<?php

namespace App\Filament\Widgets;

use App\Models\Dealer;
use App\Models\Product;
use App\Models\Transfer;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        return [
            Stat::make('Toplam Kullanıcılar', User::count())
                ->description('Sistemdeki toplam kullanıcı sayısı')
                ->descriptionIcon('heroicon-o-users')
                ->color('success'),

            Stat::make('Toplam Bayiler', Dealer::count())
                ->description('Aktif bayi sayısı')
                ->descriptionIcon('heroicon-o-building-office')
                ->color('info'),

            Stat::make('Toplam Ürünler', Product::count())
                ->description('Sistemdeki toplam ürün sayısı')
                ->descriptionIcon('heroicon-o-cube')
                ->color('warning'),

            Stat::make('Toplam Transferler', Transfer::count())
                ->description('Gerçekleştirilen transfer sayısı')
                ->descriptionIcon('heroicon-o-truck')
                ->color('primary'),
        ];
    }
}
