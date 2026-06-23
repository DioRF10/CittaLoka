<?php

namespace App\Filament\Resources\Payouts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PayoutForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Payout')
                    ->columns(2)
                    ->schema([
                        TextInput::make('host.user.name')
                            ->label('Host')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('booking.kode_booking')
                            ->label('Kode Booking')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('jumlah_bruto')
                            ->label('Jumlah Bruto')
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('komisi_rate')
                            ->label('Rate Komisi (%)')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('komisi_platform')
                            ->label('Komisi Platform')
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('jumlah_bersih')
                            ->label('Jumlah Bersih (Diterima Host)')
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated(false),
                    ]),

                Section::make('Kelola Pencairan')
                    ->columns(2)
                    ->schema([
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'processing' => 'Sedang Diproses',
                                'completed' => 'Selesai',
                                'failed' => 'Gagal',
                            ])
                            ->default('pending')
                            ->required()
                            ->native(false),

                        TextInput::make('bank_transfer_ref')
                            ->label('No. Referensi Transfer'),

                        DateTimePicker::make('paid_at')
                            ->label('Dibayar Pada'),
                    ]),
            ]);
    }
}
