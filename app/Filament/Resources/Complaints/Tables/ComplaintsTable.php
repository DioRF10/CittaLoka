<?php

namespace App\Filament\Resources\Complaints\Tables;

use App\Models\Complaint;
use App\Notifications\ComplaintResolvedNotification;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ComplaintsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('booking.kode_booking')
                    ->label('Kode Booking')
                    ->searchable(),

                TextColumn::make('filed_by_role')
                    ->label('Diajukan Oleh')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $state === 'host' ? 'Host' : 'Traveler')
                    ->color(fn (string $state): string => $state === 'host' ? 'warning' : 'info'),

                TextColumn::make('filedBy.name')
                    ->label('Nama')
                    ->searchable(),

                TextColumn::make('category')
                    ->label('Kategori')
                    ->formatStateUsing(fn (Complaint $record): string => $record->getCategoryLabel()),

                TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(40),

                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (Complaint $record): string => $record->getStatusLabel())
                    ->color(fn (string $state): string => match ($state) {
                        'resolved' => 'success',
                        'in_review' => 'warning',
                        'dismissed' => 'gray',
                        default => 'danger',
                    }),

                TextColumn::make('created_at')
                    ->label('Diajukan')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Menunggu Ditinjau',
                        'in_review' => 'Sedang Ditinjau',
                        'resolved' => 'Terselesaikan',
                        'dismissed' => 'Ditolak',
                    ]),

                SelectFilter::make('filed_by_role')
                    ->label('Diajukan Oleh')
                    ->options([
                        'traveler' => 'Traveler',
                        'host' => 'Host',
                    ]),

                SelectFilter::make('category')
                    ->options([
                        'no_show' => 'Tidak Hadir (No-Show)',
                        'not_as_described' => 'Tidak Sesuai Deskripsi',
                        'safety_concern' => 'Masalah Keamanan',
                        'payment_issue' => 'Masalah Pembayaran',
                        'inappropriate_behavior' => 'Perilaku Tidak Pantas',
                        'other' => 'Lainnya',
                    ]),
            ])
            ->recordActions([
                Action::make('mulai_tinjau')
                    ->label('Mulai Tinjau')
                    ->icon('heroicon-o-eye')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->visible(fn (Complaint $record): bool => $record->status === 'pending')
                    ->action(function (Complaint $record): void {
                        $record->update(['status' => 'in_review']);

                        Notification::make()
                            ->title('Complaint sedang ditinjau')
                            ->success()
                            ->send();
                    }),

                Action::make('selesaikan')
                    ->label('Selesaikan')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Complaint $record): bool => in_array($record->status, ['pending', 'in_review']))
                    ->schema([
                        Textarea::make('resolution_notes')
                            ->label('Catatan Penyelesaian')
                            ->helperText('Akan dikirim ke pengaju complaint.')
                            ->required(),

                        Checkbox::make('give_refund')
                            ->label('Beri refund ke traveler')
                            ->live()
                            ->visible(fn (Complaint $record): bool => $record->filed_by_role === 'traveler'
                                && $record->booking->refund_status === 'not_applicable'),

                        TextInput::make('refund_amount')
                            ->label('Nominal Refund')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(fn (Get $get): bool => (bool) $get('give_refund'))
                            ->visible(fn (Get $get): bool => (bool) $get('give_refund'))
                            ->maxValue(fn (Complaint $record): float => (float) $record->booking->total_harga)
                            ->helperText(fn (Complaint $record): string => 'Maksimal Rp ' . number_format($record->booking->total_harga, 0, ',', '.') . ' (total harga booking).'),
                    ])
                    ->action(function (Complaint $record, array $data): void {
                        $record->update([
                            'status' => 'resolved',
                            'resolution_notes' => $data['resolution_notes'],
                            'resolved_by_user_id' => auth()->id(),
                            'resolved_at' => now(),
                        ]);

                        if (!empty($data['give_refund']) && !empty($data['refund_amount'])) {
                            $booking = $record->booking;
                            $amount = (float) $data['refund_amount'];
                            $percentage = $booking->total_harga > 0
                                ? (int) round(($amount / $booking->total_harga) * 100)
                                : null;

                            $booking->update([
                                'status' => 'refunded',
                                'refund_amount' => $amount,
                                'refund_percentage' => $percentage,
                                'refund_status' => 'pending',
                                'refund_note' => 'Refund atas Complaint #' . $record->id . ' (' . $record->getCategoryLabel() . ')',
                                'cancel_reason' => 'Refund atas Complaint #' . $record->id . ': ' . $record->getCategoryLabel(),
                                'cancelled_by' => 'admin',
                                'cancelled_at' => now(),
                            ]);
                        }

                        $record->filedBy->notify(new ComplaintResolvedNotification($record));

                        Notification::make()
                            ->title('Complaint diselesaikan')
                            ->success()
                            ->send();
                    }),

                Action::make('tolak')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (Complaint $record): bool => in_array($record->status, ['pending', 'in_review']))
                    ->schema([
                        Textarea::make('resolution_notes')
                            ->label('Alasan Penolakan')
                            ->helperText('Akan dikirim ke pengaju complaint.')
                            ->required(),
                    ])
                    ->action(function (Complaint $record, array $data): void {
                        $record->update([
                            'status' => 'dismissed',
                            'resolution_notes' => $data['resolution_notes'],
                            'resolved_by_user_id' => auth()->id(),
                            'resolved_at' => now(),
                        ]);

                        $record->filedBy->notify(new ComplaintResolvedNotification($record));

                        Notification::make()
                            ->title('Complaint ditolak')
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