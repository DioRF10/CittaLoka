<?php

namespace App\Filament\Resources\SeasonalEvents\Pages;

use App\Filament\Resources\SeasonalEvents\SeasonalEventResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSeasonalEvent extends CreateRecord
{
    protected static string $resource = SeasonalEventResource::class;
}
