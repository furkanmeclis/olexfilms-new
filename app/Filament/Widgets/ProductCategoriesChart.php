<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\ProductCategory;
use Filament\Widgets\ChartWidget;

class ProductCategoriesChart extends ChartWidget
{
    protected ?string $heading = 'Ürün Kategorileri Dağılımı';
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = [
        'md' => 2,
        'xl' => 1,
    ];
    protected ?string $maxHeight = '250px';


    protected function getData(): array
    {
        $categories = ProductCategory::withCount('products')->get();
        
        $labels = $categories->pluck('name')->toArray();
        $data = $categories->pluck('products_count')->toArray();
        
        // Renk paleti
        $colors = [
            'rgb(239, 68, 68)',   // Kırmızı
            'rgb(34, 197, 94)',   // Yeşil
            'rgb(59, 130, 246)',  // Mavi
            'rgb(245, 158, 11)',  // Sarı
            'rgb(139, 92, 246)',  // Mor
            'rgb(236, 72, 153)',  // Pembe
            'rgb(6, 182, 212)',   // Cyan
            'rgb(251, 146, 60)',  // Turuncu
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Ürün Sayısı',
                    'data' => $data,
                    'backgroundColor' => array_slice($colors, 0, count($data)),
                    'borderWidth' => 2,
                    'borderColor' => '#ffffff',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
