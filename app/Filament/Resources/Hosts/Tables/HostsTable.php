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
