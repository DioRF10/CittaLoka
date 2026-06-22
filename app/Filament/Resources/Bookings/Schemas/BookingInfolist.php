<?php

namespace App\Filament\Resources\Bookings\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class BookingInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('kode_booking')
                    ->label('Kode Booking'),
                TextEntry::make('user.name')
                    ->label('Dipesan oleh'),
                TextEntry::make('experience_title_snapshot')
                    ->label('Experience'),
                TextEntry::make('host_name_snapshot')
                    ->label('Host'),
                TextEntry::make('location_snapshot')
                    ->label('Lokasi'),
                TextEntry::make('harga_per_orang_snapshot')
                    ->label('Harga per Orang')
                    ->money('IDR'),
                TextEntry::make('tanggal_experience')
                    ->label('Tanggal Experience')
                    ->date(),
                TextEntry::make('jam_experience')
                    ->label('Jam')
                    ->time()
                    ->placeholder('-'),
                TextEntry::make('jumlah_peserta')
                    ->label('Jumlah Peserta')
                    ->numeric(),
                IconEntry::make('is_private')
                    ->label('Private')
                    ->boolean(),
                TextEntry::make('total_harga')
                    ->label('Total Harga')
                    ->money('IDR'),
                TextEntry::make('platform_fee')
                    ->label('Platform Fee')
                    ->money('IDR'),
                TextEntry::make('host_earning')
                    ->label('Pendapatan Host')
                    ->money('IDR'),
                TextEntry::make('discount_amount')
                    ->label('Diskon')
                    ->money('IDR'),
                TextEntry::make('notes_for_host')
                    ->label('Catatan untuk Host')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('payment_status')
                    ->label('Status Pembayaran')
                    ->badge(),
                TextEntry::make('cancelled_at')
                    ->label('Dibatalkan Pada')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('cancel_reason')
                    ->label('Alasan Pembatalan')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('completed_at')
                    ->label('Selesai Pada')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->label('Dibuat')
                    ->dateTime(),
            ]);
    }
}
