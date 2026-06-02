<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label("نام و نام خانوادگی")
                    ->required(),
                TextInput::make('email')
                    ->label('ایمیل')
                    ->email()
                    ->required(),
                DateTimePicker::make('email_verified_at')
                ->label("تایید شده در"),
                TextInput::make('password')
                    ->label("رمز")
                    ->password()
                    ->required(),
                Toggle::make('is_admin')
                    ->label("ادمین")
                    ->required(),
            ]);
    }
}
