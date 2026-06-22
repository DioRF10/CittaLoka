<?php

namespace App\Filament\Resources\Bookings\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Booking')
                    ->description('Data ini diambil otomatis saat booking dibuat, tidak bisa diedit.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('kode_booking')
                            ->label('Kode Booking')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('user.name')
                            ->label('Dipesan oleh')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('experience_title_snapshot')
                            ->label('Experience')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('host_name_snapshot')
                            ->label('Host')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('location_snapshot')
                            ->label('Lokasi')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('tanggal_experience')
                            ->label('Tanggal Experience')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('jam_experience')
                            ->label('Jam')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('jumlah_peserta')
                            ->label('Jumlah Peserta')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('total_harga')
                            ->label('Total Harga')
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('host_earning')
                            ->label('Pendapatan Host')
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated(false),

                        Textarea::make('notes_for_host')
                            ->label('Catatan untuk Host')
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull(),
                    ]),

                Section::make('Kelola Status')
                    ->columns(2)
                    ->schema([
                        Select::make('status')
                            ->options([
                                'pending_payment' => 'Menunggu Pembayaran',
                                'confirmed' => 'Dikonfirmasi',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                                'expired' => 'Kedaluwarsa',
                                'refunded' => 'Dikembalikan',
                            ])
                            ->required()
                            ->native(false),

                        Select::make('payment_status')
                            ->options([
                                'unpaid' => 'Belum Bayar',
                                'pending' => 'Pending',
                                'paid' => 'Lunas',
                                'failed' => 'Gagal',
                                'expired' => 'Kedaluwarsa',
                                'refunded' => 'Dikembalikan',
                            ])
                            ->required()
                            ->native(false),

                        Textarea::make('cancel_reason')
                            ->label('Alasan Pembatalan')
                            ->columnSpanFull(),

                        DateTimePicker::make('cancelled_at')
                            ->label('Dibatalkan Pada'),

                        DateTimePicker::make('completed_at')
                            ->label('Selesai Pada'),
                    ]),
            ]);
    }
}
