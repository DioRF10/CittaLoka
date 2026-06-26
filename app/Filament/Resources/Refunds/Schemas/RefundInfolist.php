<?php

namespace App\Filament\Resources\Refunds\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class RefundInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('kode_booking')
                    ->label('Kode Booking'),
                TextEntry::make('user.name')
                    ->label('Traveler'),
                TextEntry::make('host.user.name')
                    ->label('Host'),
                TextEntry::make('experience_title_snapshot')
                    ->label('Experience'),
                TextEntry::make('tanggal_experience')
                    ->label('Tanggal Experience')
                    ->date('d M Y'),
                TextEntry::make('cancelled_at')
                    ->label('Dibatalkan Pada')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-'),
                TextEntry::make('cancelled_by')
                    ->label('Dibatalkan Oleh')
                    ->badge(),
                TextEntry::make('cancel_reason')
                    ->label('Alasan Pembatalan')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('total_harga')
                    ->label('Total Harga Booking')
                    ->money('IDR'),
                TextEntry::make('refund_percentage')
                    ->label('Persentase Refund')
                    ->suffix('%'),
                TextEntry::make('refund_amount')
                    ->label('Nominal Refund')
                    ->money('IDR'),
                TextEntry::make('refund_status')
                    ->label('Status Refund')
                    ->badge(),
                TextEntry::make('refunded_at')
                    ->label('Refund Dikirim Pada')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-'),
                TextEntry::make('refund_note')
                    ->label('Catatan Refund')
                    ->placeholder('-')
                    ->columnSpanFull(),
            ]);
    }
}
