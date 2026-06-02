<?php

namespace App\Filament\Resources\Lawyers;

use App\Filament\Resources\Lawyers\Pages\CreateLawyer;
use App\Filament\Resources\Lawyers\Pages\EditLawyer;
use App\Filament\Resources\Lawyers\Pages\ListLawyers;
use App\Filament\Resources\Lawyers\Schemas\LawyerForm;
use App\Filament\Resources\Lawyers\Tables\LawyersTable;
use App\Models\Lawyer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LawyerResource extends Resource
{
    protected static ?string $model = Lawyer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserCircle;

    protected static ?string $recordTitleAttribute = 'وکیل ها';
    protected static ?string $label ='وکیل ';
    public static function form(Schema $schema): Schema
    {
        return LawyerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LawyersTable::configure($table);
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
            'index' => ListLawyers::route('/'),
            'create' => CreateLawyer::route('/create'),
            'edit' => EditLawyer::route('/{record}/edit'),
        ];
    }
}
