<?php

namespace App\Filament\Widgets;

use App\Models\Skill;
use Filament\Widgets\ChartWidget;

class SkillsViewChart extends ChartWidget
{
    protected ?string $heading = 'میزان بازدید مهارت‌ها';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';
    protected ?string $description = 'نمایش تعداد بازدید هر مهارت';

    protected function getData(): array
    {
        $skills = Skill::orderBy('total_clicks', 'desc')->get();

        // تولید رنگ‌های متفاوت برای هر ستون
        $colors = [
            'rgba(255, 99, 132, 0.8)',
            'rgba(54, 162, 235, 0.8)',
            'rgba(255, 206, 86, 0.8)',
            'rgba(75, 192, 192, 0.8)',
            'rgba(153, 102, 255, 0.8)',
            'rgba(255, 159, 64, 0.8)',
        ];

        return [
            'datasets' => [
                [
                    'label' => 'تعداد بازدید',
                    'data' => $skills->pluck('total_clicks')->toArray(),
//                    'backgroundColor' => $colors,
//                    'borderColor' => array_map(function($color) {
//                        return str_replace('0.8', '1', $color);
//                    }, $colors),
                    'borderWidth' => 2,
                    'borderRadius' => 4,
                ]
            ],
            'labels' => $skills->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ]
                ]
            ]
        ];
    }
}
