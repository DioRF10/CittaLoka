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
                TextEntry::make('kode_booking')
                    ->label('Kode Booking'),
                TextEntry::make('host.user.name')
                    ->label('Host'),
                TextEntry::make('host_earning')
                    ->label('Jumlah Payout')
                    ->money('IDR'),
                TextEntry::make('platform_fee')
                    ->label('Komisi Platform')
                    ->money('IDR'),
                TextEntry::make('xendit_disbursement_id')
                    ->label('Xendit Disbursement ID')
                    ->placeholder('-'),
                TextEntry::make('disbursement_status')
                    ->label('Status')
                    ->badge(),
                TextEntry::make('disbursement_failure_reason')
                    ->label('Alasan Gagal')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('disbursed_at')
                    ->label('Dikirim Pada')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
