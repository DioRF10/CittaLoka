<?php

namespace App\Filament\Resources\Experiences\Tables;

use App\Models\Experience;
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
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ExperiencesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('judul')
                    ->label('Judul')
                    ->getStateUsing(fn (Experience $record): string => $record->getJudul())
                    ->searchable(query: function ($query, string $search) {
                        $query->where('judul->id', 'like', "%{$search}%")
                              ->orWhere('judul->en', 'like', "%{$search}%");
                    })
                    ->limit(40),

                TextColumn::make('host.user.name')
                    ->label('Host')
                    ->searchable(),

                TextColumn::make('kategori.nama')
                    ->label('Kategori')
                    ->getStateUsing(fn (Experience $record): string => $record->kategori?->getNama() ?? '-'),

                TextColumn::make('harga')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('kabupaten')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'pending_review' => 'warning',
                        'rejected', 'inactive' => 'danger',
                        'archived' => 'gray',
                        default => 'gray',
                    }),

                IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),

                TextColumn::make('rating_avg')
                    ->label('Rating')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'pending_review' => 'Menunggu Review',
                        'active' => 'Aktif',
                        'inactive' => 'Nonaktif',
                        'rejected' => 'Ditolak',
                        'archived' => 'Diarsipkan',
                    ]),

                TernaryFilter::make('is_featured')
                    ->label('Featured'),
            ])
            ->recordActions([
                Action::make('setujui')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Experience $record): bool => $record->status !== 'active')
                    ->action(function (Experience $record): void {
                        $record->update([
                            'status' => 'active',
                            'admin_note' => null,
                        ]);

                        Notification::make()
                            ->title('Experience disetujui & aktif')
                            ->success()
                            ->send();
                    }),

                Action::make('tolak')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (Experience $record): bool => $record->status !== 'rejected')
                    ->schema([
                        Textarea::make('admin_note')
                            ->label('Alasan Penolakan')
                            ->required(),
                    ])
                    ->action(function (Experience $record, array $data): void {
                        $record->update([
                            'status' => 'rejected',
                            'admin_note' => $data['admin_note'],
                        ]);

                        Notification::make()
                            ->title('Experience ditolak')
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
