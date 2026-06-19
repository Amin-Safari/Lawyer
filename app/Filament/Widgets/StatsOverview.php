<?php

namespace App\Filament\Widgets;

use App\Models\Skill;
use App\Models\SkillClick;
use App\Models\WalletTransaction;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 4;
    protected ?string $heading = ' داشبورد آمار';
    protected function getStats(): array
    {
        $totalClicks = Skill::sum('total_clicks');
        $totalCalls = SkillClick::count();
        $totalWalletCharge = WalletTransaction::where('status', 'completed')->sum('amount') * 0.1;
        $todayClicks = Skill::whereDate('created_at', Carbon::today())->sum('total_clicks');
        $todayCalls = SkillClick::whereDate('created_at', Carbon::today())->count();
        $todayWallet = WalletTransaction::where('status', 'completed')
                ->whereDate('created_at', Carbon::today())
                ->sum('amount') * 0.1;
        $monthClicks = Skill::whereMonth('created_at', Carbon::now()->month)->sum('total_clicks');
        $monthCalls = SkillClick::whereMonth('created_at', Carbon::now()->month)->count();
        $lastMonthClicks = Skill::whereMonth('created_at', Carbon::now()->subMonth())->sum('total_clicks');
        $clicksChange = $lastMonthClicks > 0
            ? round((($monthClicks - $lastMonthClicks) / $lastMonthClicks) * 100, 1)
            : 0;
        $lastMonthCalls = SkillClick::whereMonth('created_at', Carbon::now()->subMonth())->count();
        $callsChange = $lastMonthCalls > 0
            ? round((($monthCalls - $lastMonthCalls) / $lastMonthCalls) * 100, 1)
            : 0;
        return [
            Stat::make(' کل بازدید مهارت‌ها', number_format($totalClicks))
                ->description($clicksChange >= 0 ? "↑ {$clicksChange}% نسبت به ماه قبل" : "↓ " . abs($clicksChange) . "% نسبت به ماه قبل")
                ->descriptionIcon($clicksChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($clicksChange >= 0 ? 'success' : 'danger')
                ->chart([7, 3, 8, 5, 12, 9, 15, 11, 18, 14, 20, min($totalClicks, 100)])
                ->extraAttributes([
                    'class' => 'bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-950 dark:to-blue-900 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 cursor-pointer'
                ]),
            Stat::make(' تعداد کل تماس‌ها', number_format($totalCalls))
                ->description($callsChange >= 0 ? "↑ {$callsChange}% نسبت به ماه قبل" : "↓ " . abs($callsChange) . "% نسبت به ماه قبل")
                ->descriptionIcon($callsChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($callsChange >= 0 ? 'success' : 'danger')
                ->chart([4, 6, 3, 8, 5, 10, 7, 12, 9, 15, 11, min($totalCalls, 50)])
                ->extraAttributes([
                    'class' => 'bg-gradient-to-r from-green-50 to-green-100 dark:from-green-950 dark:to-green-900 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 cursor-pointer'
                ]),
            Stat::make(' شارژ کیف پول‌ها', number_format($totalWalletCharge) . ' تومن')
                ->description('مجموع شارژهای تکمیل شده')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('warning')
                ->chart([2, 4, 3, 6, 5, 8, 7, 10, 9, 12, 11, min($totalWalletCharge, 100)])
                ->extraAttributes([
                    'class' => 'bg-gradient-to-r from-amber-50 to-amber-100 dark:from-amber-950 dark:to-amber-900 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 cursor-pointer'
                ]),
//            Stat::make(' آمار امروز', "{$todayClicks} بازدید | {$todayCalls} تماس")
//                ->description("شارژ امروز: " . number_format($todayWallet) . " تومن")
//                ->descriptionIcon('heroicon-m-calendar-days')
//                ->color('info')
//                ->extraAttributes([
//                    'class' => 'bg-gradient-to-r from-purple-50 to-purple-100 dark:from-purple-950 dark:to-purple-900 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 cursor-pointer'
//                ]),
        ];
    }
}
