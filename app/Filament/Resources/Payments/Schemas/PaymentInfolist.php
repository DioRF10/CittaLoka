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
                TextEntry::make('booking.kode_booking')
                    ->label('Kode Booking'),
                TextEntry::make('midtrans_order_id'),
                TextEntry::make('midtrans_transaction_id')
                    ->placeholder('-'),
                TextEntry::make('gross_amount')
                    ->label('Jumlah')
                    ->money('IDR'),
                TextEntry::make('currency'),
                TextEntry::make('payment_type')
                    ->placeholder('-'),
                TextEntry::make('va_number')
                    ->placeholder('-'),
                TextEntry::make('transaction_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'settlement', 'capture' => 'success',
                        'pending' => 'warning',
                        'deny', 'cancel', 'expire', 'failure' => 'danger',
                        default => 'gray',
                    }),
                TextEntry::make('fraud_status')
                    ->placeholder('-'),
                TextEntry::make('pdf_url')
                    ->placeholder('-'),
                TextEntry::make('raw_response')
                    ->label('Raw Response (Debug)')
                    ->formatStateUsing(fn ($state) => $state ? json_encode($state, JSON_PRETTY_PRINT) : '-')
                    ->columnSpanFull(),
                TextEntry::make('transaction_time')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('settlement_time')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('expired_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
