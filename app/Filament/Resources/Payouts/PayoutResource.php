<?php

namespace App\Filament\Resources\Payouts;

use App\Filament\Resources\Payouts\Pages\ListPayouts;
use App\Filament\Resources\Payouts\Pages\ViewPayout;
use App\Filament\Resources\Payouts\Schemas\PayoutInfolist;
use App\Filament\Resources\Payouts\Tables\PayoutsTable;
use App\Models\Booking;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class PayoutResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $modelLabel = 'Payout';

    protected static ?string $pluralModelLabel = 'Payouts';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-currency-dollar';

    protected static string|UnitEnum|null $navigationGroup = 'Operasional';

    protected static ?string $navigationLabel = 'Payouts';

    protected static ?string $slug = 'payouts';

    protected static ?string $recordTitleAttribute = 'kode_booking';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PayoutInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PayoutsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPayouts::route('/'),
            'view' => ViewPayout::route('/{record}'),
        ];
    }
}
