<?php

namespace App\Filament\Resources\LeaderboardEntryResource\Pages;

use App\Filament\Resources\LeaderboardEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLeaderboardEntry extends EditRecord
{
    protected static string $resource = LeaderboardEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
