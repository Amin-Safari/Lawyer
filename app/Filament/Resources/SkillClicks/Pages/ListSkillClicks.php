<?php

namespace App\Filament\Resources\SkillClicks\Pages;

use App\Filament\Resources\SkillClicks\SkillClickResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSkillClicks extends ListRecords
{
    protected static string $resource = SkillClickResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
