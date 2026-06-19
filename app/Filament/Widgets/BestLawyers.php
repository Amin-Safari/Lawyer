<?php

namespace App\Filament\Widgets;

use App\Models\Lawyer;
use App\Models\SkillClick;
use Filament\Support\Enums\TextSize;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Illuminate\Support\Facades\DB;

class BestLawyers extends TableWidget
{
    protected static ?string $heading = ' برترین وکلای ماه';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 4;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                fn(): Builder => Lawyer::select(
                    'lawyers.id',
                    'lawyers.user_id',
                    'lawyers.avatar',
                    'lawyers.phone',
                    'lawyers.city_id',
                    DB::raw('COUNT(skill_clicks.id) as total_calls'),
                    DB::raw('ROW_NUMBER() OVER (ORDER BY COUNT(skill_clicks.id) DESC) as rank')
                )
                    ->join('skill_clicks', 'lawyers.id', '=', 'skill_clicks.lawyer_id')
                    ->where('skill_clicks.type', SkillClick::TYPE_CALL)
                    ->whereMonth('skill_clicks.created_at', now()->month)
                    ->whereYear('skill_clicks.created_at', now()->year)
                    ->groupBy(
                        'lawyers.id',
                        'lawyers.user_id',
                        'lawyers.avatar',
                        'lawyers.phone',
                        'lawyers.city_id'
                    )
                    ->having('total_calls', '>', 0)
                    ->orderByDesc('total_calls')
                    ->limit(3)
                    ->with(['user', 'city', 'skills'])
            )
            ->columns([
                Split::make([
                    TextColumn::make('rank')
                        ->label('رتبه')
                        ->formatStateUsing(fn($state) => match ($state) {
                            1 => '🥇',
                            2 => '🥈',
                            3 => '🥉',
                            default => "#$state"
                        })
                        ->alignCenter()
                        ->size(TextSize::Large)
                        ->grow(false),
                    Stack::make([
                        ImageColumn::make('avatar')
                            ->label('')
                            ->circular()
                            ->size(60)
                            ->disk('public')
                            ->extraAttributes(['class' => 'mx-auto align-center'])
                            ->alignCenter(),
                        TextColumn::make('user.name')
                            ->label('نام وکیل')
                            ->searchable()
                            ->size(TextSize::Large)
                            ->weight('bold')
                            ->alignCenter(),
                        TextColumn::make('city.name')
                            ->label('شهر')
                            ->icon('heroicon-o-map-pin')
                            ->alignCenter(),
                        TextColumn::make('phone')
                            ->label('شماره تماس')
                            ->icon('heroicon-o-phone')
                            ->copyable()
                            ->size(TextSize::Large)
                            ->copyMessage('شماره کپی شد')
                            ->alignCenter(),
                        TextColumn::make('skills')
                            ->label('مهارت‌ها')
                            ->formatStateUsing(function ($record) {
                                $skills = $record->skills->pluck('name')->toArray();
                                $count = $record->skills->count();
                                if ($count > 3) {
                                    $skills[] = "+" . ($count - 3);
                                }
                                return implode($skills);
                            })
                            ->badge()
                            ->color('primary')
                            ->alignCenter(),
                        TextColumn::make('total_calls')
                            ->label('تعداد تماس‌ها')
                            ->formatStateUsing(fn($state) => number_format($state) . ' تماس')
                            ->icon('heroicon-o-phone-arrow-up-right')
                            ->color('success')
                            ->size(TextSize::Large)
                            ->weight('bold')
                            ->alignCenter(),
                    ])->space(2),
                ])->from('md'),
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->filters([
                SelectFilter::make('period')
                    ->label('بازه زمانی')
                    ->options([
                        'today' => 'امروز',
                        'week' => 'این هفته',
                        'month' => 'این ماه',
                        'year' => 'امسال',
                    ])
                    ->default('month')
                    ->query(function (Builder $query, array $data) {
                        if ($data['value'] === 'today') {
                            $query->whereDate('skill_clicks.created_at', today());
                        } elseif ($data['value'] === 'week') {
                            $query->whereBetween('skill_clicks.created_at', [
                                now()->startOfWeek(),
                                now()->endOfWeek()
                            ]);
                        } elseif ($data['value'] === 'month') {
                            $query->whereMonth('skill_clicks.created_at', now()->month)
                                ->whereYear('skill_clicks.created_at', now()->year);
                        } elseif ($data['value'] === 'year') {
                            $query->whereYear('skill_clicks.created_at', now()->year);
                        }
                    }),
            ])
            ->defaultSort('total_calls', 'desc')
            ->paginated(false)
            ->striped()
            ->headerActions([
            ])
            ->actions([
            ]);
    }
}
