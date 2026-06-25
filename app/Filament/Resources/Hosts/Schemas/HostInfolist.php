<?php

namespace App\Filament\Resources\Hosts\Schemas;

use App\Models\Host;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class HostInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Informasi Personal')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('Nama Host'),
                        TextEntry::make('user.email')
                            ->label('Email'),
                        TextEntry::make('phone_number')
                            ->label('No. Telepon')
                            ->placeholder('-'),
                        TextEntry::make('age')
                            ->label('Umur')
                            ->placeholder('-'),
                        TextEntry::make('language_preference')
                            ->label('Preferensi Bahasa')
                            ->placeholder('-'),
                        TextEntry::make('village')
                            ->label('Desa/Kampung')
                            ->placeholder('-'),
                        TextEntry::make('video_url')
                            ->label('URL Video')
                            ->placeholder('-'),
                        TextEntry::make('bio')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('story')
                            ->label('Cerita Host (Story)')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('expertise')
                            ->label('Keahlian (Expertise)')
                            ->badge()
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ]),

                \Filament\Schemas\Components\Section::make('Verifikasi KTP & Selfie')
                    ->schema([
                        \Filament\Schemas\Components\Actions::make([
                            \Filament\Actions\Action::make('lihat_ktp')
                                ->label('Buka Foto KTP')
                                ->icon('heroicon-m-magnifying-glass-plus')
                                ->color('info')
                                ->visible(fn ($record) => filled($record->ktp_path))
                                ->modalHeading('Foto KTP')
                                ->modalSubmitAction(false)
                                ->modalCancelAction(false)
                                ->modalContent(fn ($record) => view('filament.components.image-modal', ['url' => asset('storage/' . $record->ktp_path)])),

                            \Filament\Actions\Action::make('lihat_selfie')
                                ->label('Buka Selfie KTP')
                                ->icon('heroicon-m-magnifying-glass-plus')
                                ->color('success')
                                ->visible(fn ($record) => filled($record->ktp_selfie_path))
                                ->modalHeading('Selfie dengan KTP')
                                ->modalSubmitAction(false)
                                ->modalCancelAction(false)
                                ->modalContent(fn ($record) => view('filament.components.image-modal', ['url' => asset('storage/' . $record->ktp_selfie_path)])),
                        ])->columnSpanFull(),

                        TextEntry::make('ktp_status')
                            ->label('Status KTP')
                            ->badge(),
                        TextEntry::make('ktp_rejection_note')
                            ->label('Catatan Penolakan')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ]),

                \Filament\Schemas\Components\Section::make('Informasi Pencairan & Bank')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('bank_name')
                            ->label('Bank')
                            ->placeholder('-'),
                        TextEntry::make('bank_account_number')
                            ->label('No. Rekening')
                            ->placeholder('-'),
                        TextEntry::make('bank_account_name')
                            ->label('Nama Pemilik')
                            ->placeholder('-'),
                        TextEntry::make('bank_account_holder')
                            ->label('Nama Pemilik (Xendit)')
                            ->placeholder('-'),
                        TextEntry::make('bank_review_status')
                            ->label('Status Verifikasi')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'verified' => 'success',
                                'needs_review' => 'warning',
                                default => 'gray',
                            }),
                        TextEntry::make('bank_review_note')
                            ->label('Catatan Bank')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ]),

                \Filament\Schemas\Components\Section::make('Statistik & Status Akun')
                    ->columns(3)
                    ->schema([
                        IconEntry::make('is_active')
                            ->label('Aktif')
                            ->boolean(),
                        IconEntry::make('is_verified')
                            ->label('Terverifikasi')
                            ->boolean(),
                        TextEntry::make('rating_avg')
                            ->label('Rating')
                            ->numeric(),
                        TextEntry::make('total_reviews')
                            ->label('Total Review')
                            ->numeric(),
                        TextEntry::make('created_at')
                            ->label('Bergabung')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('deleted_at')
                            ->label('Dihapus')
                            ->dateTime()
                            ->visible(fn (Host $record): bool => $record->trashed()),
                    ]),
            ]);
    }
}
