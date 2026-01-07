<?php

namespace App\Filament\Resources\Wallets\Tables;

use App\Models\Wallet;
use App\Services\WalletService;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class WalletsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('کاربر')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('wallet_number')
                    ->label('شماره کیف پول')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('شماره کیف پول کپی شد'),
                TextColumn::make('balance')
                    ->label('موجودی')
                    ->money('IRR')
                    ->sortable()
                    ->color(fn ($record) => $record->balance > 0 ? 'success' : 'danger')
                    ->icon(fn ($record) => $record->balance > 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down'),
                TextColumn::make('pending_balance')
                    ->label('موجودی در انتظار')
                    ->money('IRR')
                    ->sortable()
                    ->color('warning'),
                BadgeColumn::make('status')
                    ->label('وضعیت')
                    ->colors([
                        'success' => 'active',
                        'gray' => 'inactive',
                        'danger' => 'suspended',
                    ])
                    ->formatStateUsing(fn ($state): string => match ($state) {
                        'active' => 'فعال',
                        'inactive' => 'غیرفعال',
                        'suspended' => 'مسدود',
                        default => $state,
                    }),
                TextColumn::make('transactions_count')
                    ->label('تعداد تراکنش‌ها')
                    ->counts('transactions')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('تاریخ ایجاد')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                ])
            ->filters([
                SelectFilter::make('status')
                    ->label('وضعیت')
                    ->options([
                        'active' => 'فعال',
                        'inactive' => 'غیرفعال',
                        'suspended' => 'مسدود',
                    ]),
                Filter::make('has_balance')
                    ->label('دارای موجودی')
                    ->query(fn (Builder $query): Builder => $query->where('balance', '>', 0)),
                Filter::make('no_balance')
                    ->label('بدون موجودی')
                    ->query(fn (Builder $query): Builder => $query->where('balance', '<=', 0)),

            ])
            ->recordActions([
//                Action::make('transactions')
//                    ->label('تراکنش‌ها')
//                    ->icon('heroicon-o-currency-dollar')
//                    ->url(fn (Wallet $record): string => WalletTransactionResource::getUrl('index', [
//                        'tableFilters[wallet_id]' => $record->id,
//                    ])),
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                ActionGroup::make([
                    Action::make('deposit')
                        ->label('واریز')
                        ->icon('heroicon-o-plus-circle')
                        ->color('success')
                        ->form([
                            TextInput::make('amount')
                                ->label('مبلغ (ریال)')
                                ->numeric()
                                ->required()
                                ->minValue(10000)
                            ->step(10000),

                            TextInput::make('description')
                                ->label('توضیحات')
                                ->maxLength(255),
                        ])
                        ->action(function (Wallet $record, array $data): void {
                            $walletService = app(WalletService::class);
                            $walletService->deposit($record, $data['amount'], [
                                'description' => $data['description'] ?? 'واریز دستی توسط ادمین',
                                'gateway' => 'manual',
                                'reference_id' => 'ADMIN_' . now()->timestamp,
                            ]);

                            Notification::make()
                                ->title('واریز با موفقیت انجام شد')
                                ->success()
                                ->send();
                        }),

                    Action::make('withdraw')
                        ->label('برداشت')
                        ->icon('heroicon-o-minus-circle')
                        ->color('danger')
                        ->form([
                            TextInput::make('amount')
                                ->label('مبلغ (ریال)')
                                ->numeric()
                                ->required()
                                ->minValue(10000)
                            ->step(10000),

                           TextInput::make('description')
                                ->label('توضیحات')
                                ->maxLength(255),
                        ])
                        ->action(function (Wallet $record, array $data): void {
                            $walletService = app(WalletService::class);
                            $walletService->withdraw($record, $data['amount'], [
                                'description' => $data['description'] ?? 'برداشت دستی توسط ادمین',
                                'gateway' => 'manual',
                                'reference_id' => 'ADMIN_' . now()->timestamp,
                            ]);

                            Notification::make()
                                ->title('برداشت با موفقیت انجام شد')
                                ->success()
                                ->send();
                        }),
                ]),

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('change_status')
                        ->label('تغییر وضعیت')
                        ->icon('heroicon-o-adjustments-horizontal')
                        ->form([
                            Select::make('status')
                                ->label('وضعیت جدید')
                                ->options([
                                    'active' => 'فعال',
                                    'inactive' => 'غیرفعال',
                                    'suspended' => 'مسدود',
                                ])
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data): void {
                            $records->each->update(['status' => $data['status']]);
                        }),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
