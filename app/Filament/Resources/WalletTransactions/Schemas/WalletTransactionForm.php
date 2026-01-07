<?php

namespace App\Filament\Resources\WalletTransactions\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class WalletTransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('wallet_id')
                    ->label('کیف پول')
                    ->relationship('wallet', 'wallet_number')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->disabledOn('edit'),
                Select::make('user_id')
                    ->label('کاربر')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->disabledOn('edit'),
                TextInput::make('transaction_id')
                    ->label('شناسه تراکنش')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(50),
                Select::make('type')
                    ->label('نوع تراکنش')
                    ->options([
                        'deposit' => 'واریز',
                        'withdrawal' => 'برداشت',
                        'transfer' => 'انتقال',
                        'payment' => 'پرداخت',
                        'refund' => 'عودت',
                        'bonus' => 'پاداش',
                        'fee' => 'کارمزد',
                    ])
                    ->required()
                    ->disabledOn('edit'),
                TextInput::make('amount')
                    ->label('مبلغ')
                    ->numeric()
                    ->required()
                    ->prefix('ریال')
                    ->disabledOn('edit'),
                TextInput::make('balance_before')
                    ->label('موجودی قبل')
                    ->numeric()
                    ->prefix('ریال')
                    ->disabled(),
                TextInput::make('balance_after')
                    ->label('موجودی بعد')
                    ->numeric()
                    ->prefix('ریال')
                    ->disabled(),
                Select::make('status')
                    ->label('وضعیت')
                    ->options([
                        'pending' => 'در انتظار',
                        'completed' => 'تکمیل شده',
                        'failed' => 'ناموفق',
                        'cancelled' => 'لغو شده',
                    ])
                    ->required()
                    ->default('pending'),
                TextInput::make('gateway')
                    ->label('درگاه پرداخت')
                    ->maxLength(50),
                TextInput::make('reference_id')
                    ->label('شناسه مرجع')
                    ->maxLength(100),
                Textarea::make('description')
                    ->label('توضیحات')
                    ->columnSpanFull(),
                KeyValue::make('metadata')
                    ->label('اطلاعات اضافی')
                    ->columnSpanFull(),
                DateTimePicker::make('completed_at')
                    ->label('تاریخ تکمیل')
                    ->disabled(),
                DateTimePicker::make('failed_at')
                    ->label('تاریخ ناموفق')
                    ->disabled(),
                Textarea::make('failed_reason')
                    ->label('دلیل ناموفق')
                    ->disabled(),
                TextInput::make('ip_address')
                    ->label('ip')
                    ->disabled(),
            ]);
    }
}
