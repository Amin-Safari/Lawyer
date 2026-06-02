<?php

namespace App\Filament\Resources\Lawyers\Schemas;

use App\Models\City;
use App\Models\Province;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class LawyerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('کاربر')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->disabledOn('edit'),
                FileUpload::make('avatar')
                    ->label("آواتار")
                    ->image()
                    ->directory('avatars')
                    ->maxSize(2048)
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('1:1')
                    ->imageResizeTargetWidth('300')
                    ->imageResizeTargetHeight('300'),
                Textarea::make('description')
                    ->label('توضیحات')
                    ->rows(3)
                    ->maxLength(1000)
                    ->columnSpanFull(),
                Select::make('province_id')
                    ->label("استان")
                    ->relationship('province')
                    ->options(Province::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn(Set $set) => $set('city_id', null)),
                Select::make('city_id')
                    ->label('شهر')
                    ->options(function (Get $get) {
                        $provinceId = $get('province_id');
                        if (!$provinceId) {
                            return [];
                        }
                        return City::where('province_id', $provinceId)
                            ->pluck('name', 'id');
                    })
                    ->searchable()
                    ->preload()
                    ->required(),
                Textarea::make('address')
                    ->label("آدرس")
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),
                TextInput::make('phone')
                    ->label("شماره تماس")
                    ->tel()
                    ->prefix('09')
                    ->maxLength(11)
                    ->rules(['regex:/^09\d{9}$/'])
                    ->required()
                    ->helperText('09121231234'),
                    TextInput::make('attorneys_license')
                        ->label("پروانه وکالت")
                ->required()
                ->maxLength(50),
                Select::make('skills')
                    ->label("تخصص ها")
                    ->relationship('skills', 'name')
                    ->multiple()
                    ->required()
                    ->searchable()
                    ->preload()
                    ->columnSpanFull(),
            ]);
    }
}
