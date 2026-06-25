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
