<?php

namespace App\Filament\Resources\Skills;

use App\Filament\Resources\Skills\Pages\CreateSkills;
use App\Filament\Resources\Skills\Pages\EditSkills;
use App\Filament\Resources\Skills\Pages\ListSkills;
use App\Filament\Resources\Skills\Schemas\SkillsForm;
use App\Filament\Resources\Skills\Tables\SkillsTable;
use App\Models\Skill;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SkillsResource extends Resource
{
    protected static ?string $model = Skill::class;
    protected static string|null|\UnitEnum $navigationGroup = 'مدیریت مهارت ها';
    protected static ?int $navigationSort = 1;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'تخصص ها';
    protected static ?string $label ='تخصص ';

    public static function form(Schema $schema): Schema
    {
        return SkillsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SkillsTable::configure($table);
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
            'index' => ListSkills::route('/'),
            'create' => CreateSkills::route('/create'),
            'edit' => EditSkills::route('/{record}/edit'),
        ];
    }
}
