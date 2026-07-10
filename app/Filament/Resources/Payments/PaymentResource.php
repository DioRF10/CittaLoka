<?php

namespace App\Filament\Resources\Payments;

use App\Filament\Resources\Payments\Pages\ListPayments;
use App\Filament\Resources\Payments\Pages\ViewPayment;
use App\Filament\Resources\Payments\Schemas\PaymentInfolist;
use App\Filament\Resources\Payments\Tables\PaymentsTable;
use App\Models\Booking;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class PaymentResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $modelLabel = 'Payment';

    protected static ?string $pluralModelLabel = 'Payments';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static string|UnitEnum|null $navigationGroup = 'Operasional';

    protected static ?string $navigationLabel = 'Payments';

    protected static ?string $slug = 'payments';

    protected static ?string $recordTitleAttribute = 'kode_booking';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PaymentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PaymentsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPayments::route('/'),
            'view' => ViewPayment::route('/{record}'),
        ];
    }
}
