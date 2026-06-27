<?php

namespace App\Filament\Resources\Refunds\Tables;

use App\Models\Booking;
use App\Notifications\RefundProcessedNotification;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class RefundsTable
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

                TextColumn::make('cancelled_at')
                    ->label('Dibatalkan Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                TextColumn::make('cancelled_by')
                    ->label('Oleh')
                    ->badge(),

                TextColumn::make('refund_percentage')
                    ->label('Persentase')
                    ->suffix('%')
                    ->sortable(),

                TextColumn::make('refund_amount')
                    ->label('Nominal Refund')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('refund_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'success' => 'success',
                        'processing' => 'warning',
                        'failed' => 'danger',
                        'pending' => 'gray',
                        default => 'gray',
                    }),

                TextColumn::make('refunded_at')
                    ->label('Diproses Pada')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-'),
            ])
            ->filters([
                SelectFilter::make('refund_status')
                    ->label('Status Refund')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Diproses',
                        'success' => 'Berhasil',
                        'failed' => 'Gagal',
                        'not_applicable' => 'Tidak Ada Refund',
                    ]),
            ])
            ->recordActions([
                Action::make('proses_refund')
                    ->label('Proses Refund')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalDescription('Konfirmasi bahwa refund sudah ditransfer secara manual ke traveler. Catat nomor referensi transfer di bawah.')
                    ->visible(fn (Booking $record): bool => $record->refund_status === 'pending')
                    ->schema([
                        TextInput::make('transfer_ref')
                            ->label('No. Referensi Transfer')
                            ->required()
                            ->helperText('Nomor referensi transfer bank atau e-wallet sebagai bukti refund sudah dikirim.'),
                    ])
                    ->action(function (Booking $record, array $data): void {
                        $record->update([
                            'refund_status' => 'success',
                            'refunded_at'   => now(),
                            'refund_note'   => 'Refund manual. Ref: ' . $data['transfer_ref'],
                        ]);

                        $record->user?->notify(new RefundProcessedNotification($record));

                        Notification::make()
                            ->title('Refund berhasil ditandai selesai')
                            ->success()
                            ->send();
                    }),

                Action::make('tandai_gagal')
                    ->label('Tandai Gagal')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (Booking $record): bool => $record->refund_status === 'pending')
                    ->schema([
                        TextInput::make('failure_reason')
                            ->label('Alasan Gagal')
                            ->required(),
                    ])
                    ->action(function (Booking $record, array $data): void {
                        $record->update([
                            'refund_status' => 'failed',
                            'refund_note'   => 'Gagal: ' . $data['failure_reason'],
                        ]);

                        Notification::make()
                            ->title('Refund ditandai gagal')
                            ->danger()
                            ->send();
                    }),

                ViewAction::make(),
            ])
            ->defaultSort('cancelled_at', 'desc');
    }
}