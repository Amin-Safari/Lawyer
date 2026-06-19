<?php

namespace App\Filament\Resources\SkillClicks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SkillClicksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('skill.name')
                    ->label('مهارت')
                    ->searchable(),
                TextColumn::make('lawyer.id')
                    ->label('وکیل')
                    ->searchable(),
                TextColumn::make('clicker_id')
                    ->label('کلیک کننده')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ip_address')
                    ->label('آدرس آی پی')
                    ->searchable(),
                TextColumn::make('user_agent')
                    ->label("دستگاه کاربر")
                    ->searchable(),
                TextColumn::make('referrer')
                    ->label("ارجاع دهنده")
                    ->searchable(),
                TextColumn::make('type')
                ->label("نوع")
                    ->searchable(),
                TextColumn::make('cost')
                    ->label("مبلغ")
                    ->numeric()
                    ->sortable(),
                TextColumn::make('session_id')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label("تاریخ ایحاد")
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label("تاریخ تغییر")
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
