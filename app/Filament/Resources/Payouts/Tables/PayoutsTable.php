<?php

namespace App\Filament\Resources\Payouts\Tables;

use App\Models\Payout;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
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
            ->columns([
                TextColumn::make('host.user.name')
                    ->label('Host')
                    ->searchable(),

                TextColumn::make('booking.kode_booking')
                    ->label('Kode Booking')
                    ->searchable(),

                TextColumn::make('jumlah_bruto')
                    ->label('Bruto')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('komisi_platform')
                    ->label('Komisi')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('jumlah_bersih')
                    ->label('Bersih (ke Host)')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'processing' => 'warning',
                        'failed' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('paid_at')
                    ->label('Dibayar Pada')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->placeholder('-'),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Sedang Diproses',
                        'completed' => 'Selesai',
                        'failed' => 'Gagal',
                    ]),
            ])
            ->recordActions([
                Action::make('tandai_selesai')
                    ->label('Tandai Selesai')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Payout $record): bool => $record->status !== 'completed')
                    ->schema([
                        TextInput::make('bank_transfer_ref')
                            ->label('No. Referensi Transfer')
                            ->required(),
                    ])
                    ->action(function (Payout $record, array $data): void {
                        $record->update([
                            'status' => 'completed',
                            'bank_transfer_ref' => $data['bank_transfer_ref'],
                            'paid_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Payout ditandai selesai')
                            ->success()
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
