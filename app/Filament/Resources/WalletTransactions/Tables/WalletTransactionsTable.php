<?php

namespace App\Filament\Resources\WalletTransactions\Tables;

use App\Filament\Resources\Wallets\WalletResource;
use App\Models\WalletTransaction;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class WalletTransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'DESC')
            ->columns([
                TextColumn::make('transaction_id')
                    ->label('شناسه تراکنش')
                    ->searchable()
                    ->copyable()
                    ->limit(20)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) > 20) {
                            return $state;
                        }
                        return null;
                    }),
                TextColumn::make('wallet.wallet_number')
                    ->label('کیف پول')
                    ->searchable()
                    ->url(fn($record) => WalletResource::getIndexUrl( ['record' => $record->wallet_id],'view'))
                    ->openUrlInNewTab(),
                TextColumn::make('user.name')
                    ->label('کاربر')
                    ->searchable(),
                BadgeColumn::make('type')
                    ->label('نوع')
                    ->colors([
                        'success' => ['deposit', 'refund', 'bonus'],
                        'danger' => ['withdrawal', 'payment', 'fee'],
                        'primary' => 'transfer',
                    ])
                    ->formatStateUsing(fn($state): string => match ($state) {
                        'deposit' => 'واریز',
                        'withdrawal' => 'برداشت',
                        'transfer' => 'انتقال',
                        'payment' => 'پرداخت',
                        'refund' => 'عودت',
                        'bonus' => 'پاداش',
                        'fee' => 'کارمزد',
                        default => $state,
                    }),
                TextColumn::make('amount')
                    ->label('مبلغ')
                    ->money('IRR')
                    ->sortable()
                    ->color(fn($record) => in_array($record->type, ['deposit', 'refund', 'bonus']) ? 'success' : 'danger')
                    ->formatStateUsing(fn($record) => $record->getFormattedAmount()),

                BadgeColumn::make('status')
                    ->label('وضعیت')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'completed',
                        'danger' => 'failed',
                        'gray' => 'cancelled',
                    ])
                    ->formatStateUsing(fn($state): string => match ($state) {
                        'pending' => 'در انتظار',
                        'completed' => 'تکمیل شده',
                        'failed' => 'ناموفق',
                        'cancelled' => 'لغو شده',
                        default => $state,
                    }),

                TextColumn::make('gateway')
                    ->label('درگاه')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn($state): string => match ($state) {
                        'zarinpal' => 'زرین پال',
                        'mellat' => 'ملت',
                        'saman' => 'سامان',
                        'manual' => 'دستی',
                        null => '-',
                        default => $state,
                    }),

                TextColumn::make('description')
                    ->label('توضیحات')
                    ->limit(30)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) > 30) {
                            return $state;
                        }
                        return null;
                    }),
                TextColumn::make('created_at')
                    ->label('تاریخ تراکنش')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('completed_at')
                    ->label('تاریخ تکمیل')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('نوع تراکنش')
                    ->multiple()
                    ->options([
                        'deposit' => 'واریز',
                        'withdrawal' => 'برداشت',
                        'transfer' => 'انتقال',
                        'payment' => 'پرداخت',
                        'refund' => 'عودت',
                        'bonus' => 'پاداش',
                        'fee' => 'کارمزد',
                    ]),

                SelectFilter::make('status')
                    ->label('وضعیت')
                    ->multiple()
                    ->options([
                        'pending' => 'در انتظار',
                        'completed' => 'تکمیل شده',
                        'failed' => 'ناموفق',
                        'cancelled' => 'لغو شده',
                    ]),

                SelectFilter::make('wallet_id')
                    ->label('کیف پول')
                    ->relationship('wallet', 'wallet_number')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('user_id')
                    ->label('کاربر')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),

                Filter::make('amount_range')
                    ->label('محدوده مبلغ')
                    ->form([
                        TextInput::make('min_amount')
                            ->label('حداقل مبلغ')
                            ->numeric()
                            ->placeholder('ریال'),

                        TextInput::make('max_amount')
                            ->label('حداکثر مبلغ')
                            ->numeric()
                            ->placeholder('ریال'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['min_amount'],
                                fn (Builder $query, $amount): Builder => $query->where('amount', '>=', $amount),
                            )
                            ->when(
                                $data['max_amount'],
                                fn (Builder $query, $amount): Builder => $query->where('amount', '<=', $amount),
                            );
                    }),
                Filter::make('created_at')
                    ->label('تاریخ تراکنش')
                    ->form([
                        DatePicker::make('from')
                            ->label('از تاریخ'),

                        DatePicker::make('until')
                            ->label('تا تاریخ'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                Action::make('change_status')
                    ->label('تغییر وضعیت')
                    ->icon('heroicon-o-adjustments-horizontal')
                    ->form([
                       Select::make('status')
                            ->label('وضعیت جدید')
                            ->options([
                                'pending' => 'در انتظار',
                                'completed' => 'تکمیل شده',
                                'failed' => 'ناموفق',
                                'cancelled' => 'لغو شده',
                            ])
                            ->required(),
                        Textarea::make('reason')
                            ->label('دلیل تغییر')
                            ->required()
                            ->maxLength(500),
                    ])
                    ->action(function (WalletTransaction $record, array $data): void {
                        $oldStatus = $record->status;
                        $record->update(['status' => $data['status']]);
//
//                        activity()
//                            ->performedOn($record)
//                            ->causedBy(auth()->user())
//                            ->withProperties([
//                                'old_status' => $oldStatus,
//                                'new_status' => $data['status'],
//                                'reason' => $data['reason'],
//                            ])
//                            ->log('تغییر وضعیت تراکنش');

                        Notification::make()
                            ->title('وضعیت تراکنش با موفقیت تغییر کرد')
                            ->success()
                            ->send();
                    })
                    ->visible(fn ($record) => in_array($record->status, ['pending', 'failed'])),

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    BulkAction::make('export')
                        ->label('خروجی اکسل')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(fn (Collection $records) => (new WalletTransactionExport($records))->download('transactions.xlsx')),
                ]),
            ]);
    }
}
