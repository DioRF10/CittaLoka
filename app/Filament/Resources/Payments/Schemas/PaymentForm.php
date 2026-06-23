<?php

namespace App\Filament\Resources\Payments\Schemas;

use App\Models\Payment;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Transaksi')
                    ->description('Data ini dikirim otomatis oleh Midtrans, tidak bisa diedit manual.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('booking.kode_booking')
                            ->label('Kode Booking')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('midtrans_order_id')
                            ->label('Order ID')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('midtrans_transaction_id')
                            ->label('Transaction ID')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('gross_amount')
                            ->label('Jumlah')
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('payment_type')
                            ->label('Metode Bayar')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('va_number')
                            ->label('No. VA')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('fraud_status')
                            ->disabled()
                            ->dehydrated(false),

                        Action::make('lihat_pdf')
                            ->label('Lihat Bukti Pembayaran (PDF)')
                            ->icon('heroicon-o-document')
                            ->color('gray')
                            ->url(fn (?Payment $record): ?string => $record?->pdf_url)
                            ->openUrlInNewTab()
                            ->visible(fn (?Payment $record): bool => filled($record?->pdf_url)),

                        DateTimePicker::make('transaction_time')
                            ->label('Waktu Transaksi')
                            ->disabled()
                            ->dehydrated(false),

                        DateTimePicker::make('settlement_time')
                            ->label('Waktu Settlement')
                            ->disabled()
                            ->dehydrated(false),

                        DateTimePicker::make('expired_at')
                            ->label('Kedaluwarsa Pada')
                            ->disabled()
                            ->dehydrated(false),
                    ]),

                Section::make('Kelola Status')
                    ->description('Ubah manual hanya kalau ada masalah sinkronisasi dengan Midtrans.')
                    ->schema([
                        Select::make('transaction_status')
                            ->label('Status Transaksi')
                            ->options([
                                'pending' => 'Pending',
                                'settlement' => 'Settlement',
                                'capture' => 'Capture',
                                'deny' => 'Deny',
                                'cancel' => 'Cancel',
                                'expire' => 'Expire',
                                'failure' => 'Failure',
                                'refund' => 'Refund',
                                'partial_refund' => 'Partial Refund',
                            ])
                            ->required()
                            ->native(false),
                    ]),

                Section::make('Raw Response (Debug)')
                    ->collapsed()
                    ->schema([
                        Textarea::make('raw_response_display')
                            ->label('')
                            ->disabled()
                            ->dehydrated(false)
                            ->rows(8)
                            ->columnSpanFull()
                            ->afterStateHydrated(function ($component, ?Payment $record) {
                                $component->state(
                                    $record?->raw_response
                                        ? json_encode($record->raw_response, JSON_PRETTY_PRINT)
                                        : null
                                );
                            }),
                    ]),
            ]);
    }
}
