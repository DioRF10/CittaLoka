<?php

namespace App\Filament\Resources\Payouts\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PayoutInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('host.user.name')
                    ->label('Host'),
                TextEntry::make('booking.kode_booking')
                    ->label('Kode Booking'),
                TextEntry::make('jumlah_bruto')
                    ->label('Jumlah Bruto')
                    ->money('IDR'),
                TextEntry::make('komisi_rate')
                    ->label('Rate Komisi (%)'),
                TextEntry::make('komisi_platform')
                    ->label('Komisi Platform')
                    ->money('IDR'),
                TextEntry::make('jumlah_bersih')
                    ->label('Jumlah Bersih')
                    ->money('IDR'),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('bank_transfer_ref')
                    ->label('No. Referensi Transfer')
                    ->placeholder('-'),
                TextEntry::make('paid_at')
                    ->label('Dibayar Pada')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime(),
            ]);
    }
}
