<?php

namespace App\Filament\Resources\Hosts\Tables;

use App\Models\Host;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use App\Notifications\BankApprovedNotification;
use App\Notifications\BankNeedsReviewNotification;

class HostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nama Host')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('village')
                    ->label('Desa')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('ktp_status')
                    ->label('Status KTP')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'verified' => 'success',
                        'pending' => 'warning',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),

                IconColumn::make('is_verified')
                    ->label('Terverifikasi')
                    ->boolean(),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),

                TextColumn::make('bank_review_status')
                    ->label('Status Rekening')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'verified' => 'success',
                        'needs_review' => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('rating_avg')
                    ->label('Rating')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),

                TextColumn::make('total_reviews')
                    ->label('Jml Review')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Bergabung')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('deleted_at')
                    ->label('Disuspend')
                    ->dateTime('d M Y')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('ktp_status')
                    ->label('Status KTP')
                    ->options([
                        'unverified' => 'Belum Diajukan',
                        'pending' => 'Menunggu Review',
                        'verified' => 'Terverifikasi',
                        'rejected' => 'Ditolak',
                    ]),

                TernaryFilter::make('is_active')
                    ->label('Status Aktif'),

                SelectFilter::make('bank_review_status')
                    ->label('Status Rekening')
                    ->options([
                        'not_verified' => 'Belum Verifikasi',
                        'verified' => 'Terverifikasi',
                        'needs_review' => 'Perlu Direview',
                    ]),

                TrashedFilter::make(),
            ])
            ->recordActions([
                Action::make('verifikasi')
                    ->label('Verifikasi')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Host $record): bool => $record->ktp_status !== 'verified')
                    ->action(function (Host $record): void {
                        $record->update([
                            'ktp_status' => 'verified',
                            'is_verified' => true,
                            'ktp_rejection_note' => null,
                        ]);

                        Notification::make()
                            ->title('Host berhasil diverifikasi')
                            ->success()
                            ->send();
                    }),

                Action::make('tolak')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (Host $record): bool => $record->ktp_status !== 'rejected')
                    ->schema([
                        Textarea::make('ktp_rejection_note')
                            ->label('Alasan Penolakan')
                            ->required(),
                    ])
                    ->action(function (Host $record, array $data): void {
                        $record->update([
                            'ktp_status' => 'rejected',
                            'is_verified' => false,
                            'ktp_rejection_note' => $data['ktp_rejection_note'],
                        ]);

                        Notification::make()
                            ->title('Pengajuan host ditolak')
                            ->warning()
                            ->send();
                    }),

                Action::make('setujui_rekening')
                    ->label('Setujui Rekening')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalDescription('Pastikan nama pemilik rekening memang cocok dengan host ini sebelum menyetujui.')
                    ->visible(fn (Host $record): bool => $record->bank_review_status === 'needs_review')
                    ->action(function (Host $record): void {
                        $record->update([
                            'bank_review_status' => 'verified',
                            'bank_review_note' => null,
                            'bank_reviewed_by' => auth()->id(),
                            'bank_reviewed_at' => now(),
                        ]);

                        // ── Notifikasi ke host ──
                        $record->user?->notify(new BankApprovedNotification($record));

                        Notification::make()
                            ->title('Rekening host disetujui')
                            ->success()
                            ->send();
                    }),

                Action::make('tolak_rekening')
                    ->label('Tolak Rekening')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (Host $record): bool => $record->bank_review_status === 'needs_review')
                    ->schema([
                        Textarea::make('bank_review_note')
                            ->label('Alasan Penolakan')
                            ->required(),
                    ])
                    ->action(function (Host $record, array $data): void {
                        $record->update([
                            'bank_review_status' => 'not_verified',
                            'bank_review_note' => $data['bank_review_note'],
                            'bank_reviewed_by' => auth()->id(),
                            'bank_reviewed_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Rekening host ditolak, host perlu submit ulang')
                            ->warning()
                            ->send();
                    }),

                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}