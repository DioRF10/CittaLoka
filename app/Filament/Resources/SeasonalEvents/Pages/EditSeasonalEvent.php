<?php

namespace App\Filament\Resources\SeasonalEvents\Pages;

use App\Filament\Resources\SeasonalEvents\SeasonalEventResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSeasonalEvent extends EditRecord
{
    protected static string $resource = SeasonalEventResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
