<?php

namespace App\Filament\Widgets;

use App\Models\Transfer;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class MonthlyTransfersChart extends ChartWidget
{
    protected ?string $heading = 'Aylık Transfer Grafiği';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = [
        'md' => 2,
        'xl' => 1,
    ];

    protected function getData(): array
    {
        $data = [];
        $labels = [];
        
        // Son 12 ayın verilerini al
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthName = $date->locale('tr')->isoFormat('MMM YYYY');
            
            $count = Transfer::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            
            $labels[] = $monthName;
            $data[] = $count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Transfer Sayısı',
                    'data' => $data,
                    'borderColor' => 'rgb(34, 197, 94)',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
