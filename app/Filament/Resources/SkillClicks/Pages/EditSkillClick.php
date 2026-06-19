<?php

namespace App\Filament\Resources\SkillClicks\Pages;

use App\Filament\Resources\SkillClicks\SkillClickResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSkillClick extends EditRecord
{
    protected static string $resource = SkillClickResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
