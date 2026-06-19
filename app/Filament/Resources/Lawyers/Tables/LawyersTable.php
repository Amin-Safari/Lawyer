<?php

namespace App\Filament\Resources\Lawyers\Tables;

use App\Models\Lawyer;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\TextSize;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class LawyersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user_id')
                    ->label('User Id')
                    ->searchable()
                    ->sortable()
                ->toggleable(),
                TextColumn::make('user.name')
                    ->label('نام و نام خانوادگی')
                    ->searchable()
                    ->sortable()
                ->toggleable(),
                ImageColumn::make('avatar')
                    ->label('آواتار')
                    ->disk('public')
                    ->circular()
                    ->imageHeight(40)
                    ->defaultImageUrl('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT7gTERsv3nO-4I-R9C00Uor_m_nmxT0sE9Cg&s'),
                TextColumn::make('attorneys_license')
                    ->toggleable()
                    ->label('پروانه وکالت'),
                TextColumn::make('description')
                    ->label('توضیحات')
                    ->wrap()
                    ->toggleable()
                    ->size(TextSize::ExtraSmall)
                    ->limit(50, end: '...'),
                TextColumn::make('province.name')
                    ->sortable()
                ->label('استان')
                ->searchable()
                ->toggleable(),
                TextColumn::make('city.name')
                    ->label('شهر')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('address')
                    ->label('آدرس')
                    ->wrap()
                    ->toggleable()
                    ->size(TextSize::ExtraSmall)
                    ->limit(50, end: '...'),
                TextColumn::make('phone')
                    ->label('شماره تماس')
                    ->toggleable()
                    ->searchable(),
                TagsColumn::make('skills.name')
                    ->label('تخصص ها')
                    ->toggleable()
                    ->separator(',')
                    ->searchable()
                    ->extraAttributes(['class' => 'flex gap-1'])
                    ->getStateUsing(function ($record) {
                        return $record->skills->pluck('name')->toArray();
                    }),
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
                SelectFilter::make('province')
                    ->relationship('province', 'name')
                    ->label('استان'),

                SelectFilter::make('city')
                    ->relationship('city', 'name')
                    ->label('شهر')
                    ->searchable(),])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
