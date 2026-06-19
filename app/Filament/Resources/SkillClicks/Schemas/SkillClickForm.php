<?php

namespace App\Filament\Resources\SkillClicks\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SkillClickForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('skill_id')
                    ->relationship('skill', 'name')
                    ->required(),
                Select::make('lawyer_id')
                    ->relationship('lawyer', 'id')
                    ->required(),
                TextInput::make('clicker_id')
                    ->numeric(),
                TextInput::make('ip_address'),
                TextInput::make('user_agent'),
                TextInput::make('referrer'),
                TextInput::make('type')
                    ->required()
                    ->default('click'),
                TextInput::make('cost')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->prefix('$'),
                TextInput::make('session_id'),
                Textarea::make('metadata')
                    ->columnSpanFull(),
            ]);
    }
}
