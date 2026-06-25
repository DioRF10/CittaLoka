<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PaymentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('kode_booking')
                    ->label('Kode Booking'),
                TextEntry::make('user.name')
                    ->label('Traveler'),
                TextEntry::make('experience_title_snapshot')
                    ->label('Experience'),
                TextEntry::make('total_harga')
                    ->label('Total Harga')
                    ->money('IDR'),
                TextEntry::make('xendit_invoice_id')
                    ->label('Xendit Invoice ID')
                    ->placeholder('-'),
                TextEntry::make('xendit_invoice_url')
                    ->label('Link Invoice')
                    ->placeholder('-')
                    ->url(fn ($state) => $state, true),
                TextEntry::make('xendit_payment_method')
                    ->label('Metode Pembayaran')
                    ->placeholder('-'),
                TextEntry::make('payment_status')
                    ->label('Status')
                    ->badge(),
                TextEntry::make('payment_expired_at')
                    ->label('Invoice Kedaluwarsa Pada')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('paid_at')
                    ->label('Dibayar Pada')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
