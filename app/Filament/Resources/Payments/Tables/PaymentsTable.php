<?php

namespace App\Filament\Resources\Payments\Tables;

use App\Models\Booking;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_booking')
                    ->label('Kode Booking')
                    ->searchable(),

                TextColumn::make('user.name')
                    ->label('Traveler')
                    ->searchable(),

                TextColumn::make('experience_title_snapshot')
                    ->label('Experience')
                    ->limit(30),

                TextColumn::make('total_harga')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('xendit_payment_method')
                    ->label('Metode')
                    ->placeholder('-')
                    ->toggleable(),

                TextColumn::make('payment_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'pending' => 'warning',
                        'failed', 'expired' => 'danger',
                        'refunded' => 'gray',
                        default => 'gray',
                    }),

                TextColumn::make('payment_expired_at')
                    ->label('Invoice Kedaluwarsa')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('paid_at')
                    ->label('Dibayar Pada')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('payment_status')
                    ->label('Status')
                    ->options([
                        'unpaid' => 'Belum Bayar',
                        'pending' => 'Pending',
                        'paid' => 'Lunas',
                        'failed' => 'Gagal',
                        'expired' => 'Kedaluwarsa',
                        'refunded' => 'Dikembalikan',
                    ]),
            ])
            ->recordActions([
                Action::make('tandai_lunas_manual')
                    ->label('Tandai Lunas Manual')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalDescription('Pakai ini hanya kalau pembayaran sudah masuk tapi webhook Xendit gagal/belum diterima.')
                    ->visible(fn (Booking $record): bool => $record->payment_status !== 'paid')
                    ->action(function (Booking $record): void {
                        $record->update([
                            'payment_status' => 'paid',
                            'status' => 'confirmed',
                            'paid_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Booking ditandai lunas manual')
                            ->success()
                            ->send();
                    }),

                ViewAction::make(),
            ])
            ->defaultSort('paid_at', 'desc');
    }
}
