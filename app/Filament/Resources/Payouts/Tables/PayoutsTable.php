<?php

namespace App\Filament\Resources\Payouts\Tables;

use App\Models\Booking;
use App\Services\XenditService;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PayoutsTable
{
    /**
     * Window dispute — harus sama dengan yang ada di
     * App\Console\Commands\DisburseBookingPayouts
     */
    private const DISPUTE_WINDOW_HOURS = 48;

    public static function configure(Table $table): Table
    {
        return $table
            ->query(
                Booking::query()->where('status', 'completed')
            )
            ->columns([
                TextColumn::make('kode_booking')
                    ->label('Kode Booking')
                    ->searchable(),

                TextColumn::make('host.user.name')
                    ->label('Host')
                    ->searchable(),

                TextColumn::make('host_earning')
                    ->label('Jumlah Payout')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('completed_at')
                    ->label('Selesai Pada')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->sortable(),

                TextColumn::make('dispute_window_status')
                    ->label('Window Dispute')
                    ->state(function (Booking $record): string {
                        if ($record->disbursement_status !== 'pending') {
                            return '-';
                        }
                        if (! $record->completed_at) {
                            return 'Belum lengkap';
                        }

                        $unlockAt = $record->completed_at->copy()->addHours(self::DISPUTE_WINDOW_HOURS);

                        if (now()->lt($unlockAt)) {
                            return 'Terbuka hingga ' . $unlockAt->format('d M, H:i');
                        }

                        return 'Selesai, siap dicairkan';
                    })
                    ->badge()
                    ->color(function (Booking $record): string {
                        if ($record->disbursement_status !== 'pending') {
                            return 'gray';
                        }
                        if (! $record->completed_at) {
                            return 'gray';
                        }

                        $unlockAt = $record->completed_at->copy()->addHours(self::DISPUTE_WINDOW_HOURS);

                        return now()->lt($unlockAt) ? 'warning' : 'success';
                    })
                    ->toggleable(),

                TextColumn::make('disbursement_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'success' => 'success',
                        'processing' => 'warning',
                        'failed' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('disbursement_failure_reason')
                    ->label('Alasan Gagal')
                    ->limit(30)
                    ->placeholder('-')
                    ->toggleable(),

                TextColumn::make('disbursed_at')
                    ->label('Dikirim Pada')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('disbursement_status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Diproses',
                        'success' => 'Berhasil',
                        'failed' => 'Gagal',
                    ]),
            ])
            ->recordActions([
                Action::make('retry_disbursement')
                    ->label('Coba Kirim Ulang')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->visible(fn (Booking $record): bool => $record->disbursement_status === 'failed')
                    ->action(function (Booking $record): void {
                        $host = $record->host;

                        if (! $host || ! $host->bank_name || ! $host->bank_account_number) {
                            Notification::make()
                                ->title('Data bank host belum lengkap')
                                ->danger()
                                ->send();
                            return;
                        }

                        try {
                            $xendit = app(XenditService::class);
                            $result = $xendit->createDisbursement(
                                externalId: 'disb-' . $record->id,
                                bankCode: $host->bank_name,
                                accountNumber: $host->bank_account_number,
                                accountHolderName: $host->bank_account_holder ?? $host->bank_account_name,
                                amount: (int) $record->host_earning,
                                description: 'Payout CittaLoka booking ' . $record->kode_booking,
                            );

                            $record->update([
                                'xendit_disbursement_id' => $result['id'] ?? null,
                                'disbursement_status' => 'processing',
                                'disbursement_failure_reason' => null,
                            ]);

                            Notification::make()
                                ->title('Disbursement dikirim ulang')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Gagal kirim ulang')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),

                Action::make('cairkan_sekarang')
                    ->label('Cairkan Sekarang (Lewati Window)')
                    ->icon('heroicon-o-bolt')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalDescription('Ini akan mencairkan dana SEBELUM window dispute 48 jam selesai. Hanya gunakan jika sudah dikonfirmasi tidak ada keluhan dari traveler.')
                    ->visible(function (Booking $record): bool {
                        if ($record->disbursement_status !== 'pending' || ! $record->completed_at) {
                            return false;
                        }
                        $unlockAt = $record->completed_at->copy()->addHours(self::DISPUTE_WINDOW_HOURS);
                        return now()->lt($unlockAt);
                    })
                    ->action(function (Booking $record): void {
                        $host = $record->host;

                        if (! $host || ! $host->bank_name || ! $host->bank_account_number || $host->bank_review_status !== 'verified') {
                            Notification::make()
                                ->title('Tidak bisa cairkan: data/verifikasi bank host belum lengkap')
                                ->danger()
                                ->send();
                            return;
                        }

                        try {
                            $xendit = app(XenditService::class);
                            $result = $xendit->createDisbursement(
                                externalId: 'disb-' . $record->id,
                                bankCode: $host->bank_name,
                                accountNumber: $host->bank_account_number,
                                accountHolderName: $host->bank_account_holder ?? $host->bank_account_name,
                                amount: (int) $record->host_earning,
                                description: 'Payout CittaLoka booking ' . $record->kode_booking . ' (manual override)',
                            );

                            $record->update([
                                'xendit_disbursement_id' => $result['id'] ?? null,
                                'disbursement_status' => 'processing',
                                'disbursement_failure_reason' => null,
                            ]);

                            Notification::make()
                                ->title('Disbursement dikirim (melewati window dispute)')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Gagal mencairkan')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),

                Action::make('tandai_manual')
                    ->label('Tandai Selesai Manual')
                    ->icon('heroicon-o-check-circle')
                    ->color('gray')
                    ->requiresConfirmation()
                    ->modalDescription('Pakai ini kalau payout dikirim manual lewat transfer bank biasa (bukan via Xendit).')
                    ->visible(fn (Booking $record): bool => $record->disbursement_status !== 'success')
                    ->schema([
                        TextInput::make('manual_ref')
                            ->label('No. Referensi Transfer Manual')
                            ->required(),
                    ])
                    ->action(function (Booking $record, array $data): void {
                        $record->update([
                            'disbursement_status' => 'success',
                            'disbursed_at' => now(),
                            'disbursement_failure_reason' => null,
                            'xendit_disbursement_id' => 'manual-' . $data['manual_ref'],
                        ]);

                        Notification::make()
                            ->title('Payout ditandai selesai manual')
                            ->success()
                            ->send();
                    }),

                ViewAction::make(),
            ])
            ->defaultSort('id', 'desc');
    }
}