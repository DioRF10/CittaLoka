<?php

namespace App\Filament\Resources\Refunds;

use App\Filament\Resources\Refunds\Pages\ListRefunds;
use App\Filament\Resources\Refunds\Pages\ViewRefund;
use App\Filament\Resources\Refunds\Schemas\RefundInfolist;
use App\Filament\Resources\Refunds\Tables\RefundsTable;
use App\Models\Booking;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class RefundResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-receipt-refund';

    protected static string|UnitEnum|null $navigationGroup = 'Operasional';

    protected static ?string $navigationLabel = 'Refunds';

    protected static ?string $slug = 'refunds';

    protected static ?string $recordTitleAttribute = 'kode_booking';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RefundInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RefundsTable::configure($table);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        // Tampilkan booking yang dibatalkan (refund traveler) maupun yang di-refund
        // lewat penyelesaian complaint (status 'refunded').
        return parent::getEloquentQuery()
            ->whereIn('status', ['cancelled', 'refunded'])
            ->whereNotNull('refund_percentage');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRefunds::route('/'),
            'view' => ViewRefund::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $pendingCount = static::getModel()::where('status', 'cancelled')
            ->where('refund_status', 'pending')
            ->count();

        return $pendingCount > 0 ? (string) $pendingCount : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}