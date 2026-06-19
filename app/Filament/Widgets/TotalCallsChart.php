<?php

namespace App\Filament\Widgets;

use App\Models\SkillClick;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TotalCallsChart extends ChartWidget
{
    protected static ?int $sort = 3;
    protected ?string $heading = '📞 آمار تماس‌ها';
    protected int | string | array $columnSpan = 'full';

    protected function getFilters(): ?array
    {
        return [
            'today' => 'امروز',
            '7' => '۷ روز گذشته',
            '15' => '۱۵ روز گذشته',
            '30' => '۳۰ روز گذشته',
            '60' => '۶۰ روز گذشته',
            '90' => '۹۰ روز گذشته',
            '180' => '۱۸۰ روز گذشته',
            '365' => 'یک سال گذشته',
        ];
    }

    protected function getData(): array
    {
        $filter = $this->filter ?? '30';
        $days = $filter === 'today' ? 1 : (int) $filter;

        $startDate = $filter === 'today'
            ? Carbon::today()->startOfDay()
            : Carbon::now()->subDays($days)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        // دریافت آمار تماس‌ها
        $stats = SkillClick::whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // ایجاد آرایه کامل برای تمام روزها
        $periods = $this->generateDateRange($startDate, $endDate);
        $data = [];
        $labels = [];

        foreach ($periods as $date) {
            $dateKey = $date->format('Y-m-d');
            $found = $stats->firstWhere('date', $dateKey);
            $data[] = $found ? $found->count : 0;
            $labels[] = $this->formatLabel($date, $filter);
        }

        // محاسبه مجموع و میانگین
        $total = array_sum($data);
        $avg = count($data) > 0 ? round($total / count($data), 1) : 0;
        $max = count($data) > 0 ? max($data) : 0;

        // به‌روزرسانی هدر با آمار
        $this->heading = "📞 آمار تماس‌ها (مجموع: {$total} | میانگین: {$avg} | بیشترین: {$max})";

        return [
            'datasets' => [
                [
                    'label' => 'تعداد تماس‌ها',
                    'data' => $data,
                    'backgroundColor' => array_map(function($value) {
                        if ($value == 0) return 'rgba(200, 200, 200, 0.3)';
                        if ($value <= 5) return 'rgba(54, 162, 235, 0.6)';
                        if ($value <= 10) return 'rgba(75, 192, 192, 0.7)';
                        if ($value <= 20) return 'rgba(255, 206, 86, 0.7)';
                        return 'rgba(255, 99, 132, 0.8)';
                    }, $data),
                    'borderColor' => 'rgb(54, 162, 235)',
                    'borderWidth' => 1,
                    'borderRadius' => 4,
                ]
            ],
            'labels' => $labels,
        ];
    }

    protected function generateDateRange($start, $end): array
    {
        $dates = [];
        $current = clone $start;

        while ($current <= $end) {
            $dates[] = clone $current;
            $current->addDay();
        }

        return $dates;
    }

    protected function formatLabel($date, $filter): string
    {
        if ($filter === 'today') {
            return 'امروز';
        }

        $days = is_numeric($filter) ? (int) $filter : 30;

        if ($days <= 7) {
            // برای هفته، روز هفته را نمایش بده
            $weekDays = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنجشنبه', 'جمعه'];
            return $weekDays[$date->dayOfWeek] . ' ' . $date->format('d');
        } elseif ($days <= 30) {
            return $date->format('d M');
        } elseif ($days <= 90) {
            return $date->format('d M');
        } else {
            return $date->format('M Y');
        }
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
                    'labels' => [
                        'font' => [
                            'size' => 14,
                            'weight' => 'bold'
                        ]
                    ]
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => "function(context) {
                            return 'تماس‌ها: ' + context.parsed.y.toLocaleString('fa-IR');
                        }"
                    ]
                ]
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                        'callback' => "function(value) {
                            return value.toLocaleString('fa-IR');
                        }"
                    ],
                    'grid' => [
                        'display' => true,
                        'color' => 'rgba(0, 0, 0, 0.05)'
                    ]
                ],
                'x' => [
                    'grid' => [
                        'display' => false
                    ],
                    'ticks' => [
                        'maxRotation' => 45,
                        'minRotation' => 0,
                        'autoSkip' => true,
                        'maxTicksLimit' => 30
                    ]
                ]
            ],
            'interaction' => [
                'intersect' => false,
                'mode' => 'index'
            ]
        ];
    }
}
