<?php

namespace App\Filament\Resources\SeasonalEvents\Pages;

use App\Filament\Resources\SeasonalEvents\SeasonalEventResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSeasonalEvents extends ListRecords
{
    protected static string $resource = SeasonalEventResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
