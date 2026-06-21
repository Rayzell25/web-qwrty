<?php

namespace App\Filament\Resources\LeaderboardEntryResource\Pages;

use App\Filament\Resources\LeaderboardEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLeaderboardEntries extends ListRecords
{
    protected static string $resource = LeaderboardEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
