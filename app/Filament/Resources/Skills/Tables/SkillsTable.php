<?php

namespace App\Filament\Resources\Skills\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SkillsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('name')
                ->label("نام تخصص")
                ->sortable()
                ->searchable(),
                TextColumn::make('click_price')
                ->label('قیمت کلیک')
                ->sortable()
                ->searchable()
                ,
                TextColumn::make('total_clicks')
                ->label('مجموع کلیک ها')
                ->sortable()
                ->searchable(),
                IconColumn::make('is_active')
                ->label('فعال')
                ->sortable()
                ->boolean(),
                TextColumn::make('created_at')
                    ->label('تاریخ ایجاد')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('updated_at')
                    ->label('تاریخ تغییر')
                    ->sortable()
                    ->dateTime('d/m/Y H:i')
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                DeleteAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
