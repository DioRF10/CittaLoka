<?php

namespace App\Filament\Resources\Bookings\Tables;

use App\Models\Booking;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BookingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_booking')
                    ->label('Kode')
                    ->searchable(),

                TextColumn::make('user.name')
                    ->label('Traveler')
                    ->searchable(),

                TextColumn::make('experience_title_snapshot')
                    ->label('Experience')
                    ->searchable()
                    ->limit(30),

                TextColumn::make('host_name_snapshot')
                    ->label('Host')
                    ->searchable(),

                TextColumn::make('tanggal_experience')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),

                TextColumn::make('jumlah_peserta')
                    ->label('Peserta')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('total_harga')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'confirmed' => 'success',
                        'completed' => 'success',
                        'pending_payment' => 'warning',
                        'cancelled', 'expired' => 'danger',
                        'refunded' => 'gray',
                        default => 'gray',
                    }),

                TextColumn::make('payment_status')
                    ->label('Pembayaran')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'pending' => 'warning',
                        'failed', 'expired' => 'danger',
                        'refunded' => 'gray',
                        default => 'gray',
                    }),

                IconColumn::make('is_private')
                    ->label('Private')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending_payment' => 'Menunggu Pembayaran',
                        'confirmed' => 'Dikonfirmasi',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                        'expired' => 'Kedaluwarsa',
                        'refunded' => 'Dikembalikan',
                    ]),

                SelectFilter::make('payment_status')
                    ->label('Status Pembayaran')
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
                Action::make('tandai_selesai')
                    ->label('Tandai Selesai')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Booking $record): bool => $record->status === 'confirmed')
                    ->action(function (Booking $record): void {
                        $record->update([
                            'status' => 'completed',
                            'completed_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Booking ditandai selesai')
                            ->success()
                            ->send();
                    }),

                Action::make('batalkan')
                    ->label('Batalkan')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (Booking $record): bool => ! in_array($record->status, ['cancelled', 'completed', 'refunded']))
                    ->schema([
                        Textarea::make('cancel_reason')
                            ->label('Alasan Pembatalan')
                            ->required(),
                    ])
                    ->action(function (Booking $record, array $data): void {
                        $record->update([
                            'status' => 'cancelled',
                            'cancelled_at' => now(),
                            'cancel_reason' => $data['cancel_reason'],
                        ]);

                        Notification::make()
                            ->title('Booking dibatalkan')
                            ->warning()
                            ->send();
                    }),

                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
