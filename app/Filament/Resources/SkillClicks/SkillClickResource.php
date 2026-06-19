<?php

namespace App\Filament\Resources\SkillClicks;

use App\Filament\Resources\SkillClicks\Pages\CreateSkillClick;
use App\Filament\Resources\SkillClicks\Pages\EditSkillClick;
use App\Filament\Resources\SkillClicks\Pages\ListSkillClicks;
use App\Filament\Resources\SkillClicks\Schemas\SkillClickForm;
use App\Filament\Resources\SkillClicks\Tables\SkillClicksTable;
use App\Models\SkillClick;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SkillClickResource extends Resource
{
    protected static ?string $model = SkillClick::class;
    protected static string|null|\UnitEnum $navigationGroup = 'مدیریت مهارت ها';
    protected static ?int $navigationSort = 2;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhone;

    protected static ?string $recordTitleAttribute = 'کلیک مهارت ها ';
    protected static ?string $label ='کلیک مهارت ها ';

    public static function form(Schema $schema): Schema
    {
        return SkillClickForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SkillClicksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSkillClicks::route('/'),
            'create' => CreateSkillClick::route('/create'),
            'edit' => EditSkillClick::route('/{record}/edit'),
        ];
    }
}
