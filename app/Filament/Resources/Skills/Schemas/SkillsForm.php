<?php

namespace App\Filament\Resources\Skills\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SkillsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label("نام تخصص")
                ->required()
                ->maxLength(255),
                TextInput::make('click_price')
                    ->label("قیمت کلیک")
                ->required()
                ->numeric()
                ->default(10000)
                ->step(10000)
                ->maxLength(10)
                ->prefix('ريال'),
                TextInput::make('total_clicks')
                    ->label("مجموع کلیک ها")
                ->required()
                ->numeric()
                ->default(0),
                Toggle::make('is_active')
                    ->label("فعال")
                    ->required(),
            ]);
    }
}
