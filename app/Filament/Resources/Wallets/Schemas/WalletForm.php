<?php

namespace App\Filament\Resources\Wallets\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class WalletForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('کاربر')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->disabledOn('edit'),
                TextInput::make('wallet_number')
                    ->label('شماره کیف پول')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(50),
                TextInput::make('balance')
                    ->label('موجودی')
                    ->numeric()
                    ->required()
                    ->prefix('ریال')
                    ->default(0)
                    ->disabled(),

                TextInput::make('pending_balance')
                    ->label('موجودی در انتظار')
                    ->numeric()
                    ->prefix('ریال')
                    ->default(0)
                    ->disabled(),
                Select::make('status')
                    ->label('وضعیت')
                    ->options([
                        'active' => 'فعال',
                        'inactive' => 'غیرفعال',
                        'suspended' => 'مسدود',
                    ])
                    ->required()
                    ->default('active'),
                Placeholder::make('created_at')
                    ->label('تاریخ ایجاد')
                    ->content(fn($record): string => $record?->created_at ? $record->created_at->format('d/m/Y H:i') : '-')
                    ->hiddenOn('create'),
                Placeholder::make('updated_at')
                    ->label('آخرین بروزرسانی')
                    ->content(fn ($record): string => $record?->updated_at ? $record->updated_at->format('d/m/Y H:i') : '-')
                    ->hiddenOn('create'),

            ]);
    }
}
