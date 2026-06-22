<?php

namespace App\Filament\Resources\Hosts\Schemas;

use App\Models\Host;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class HostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Host')
                    ->columns(2)
                    ->schema([
                        TextInput::make('user.name')
                            ->label('Nama')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('user.email')
                            ->label('Email')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('village')
                            ->label('Desa / Kampung'),

                        TextInput::make('video_url')
                            ->label('URL Video Profil')
                            ->url(),

                        Textarea::make('bio')
                            ->columnSpanFull(),
                    ]),

                Section::make('Verifikasi KTP')
                    ->columns(2)
                    ->schema([
                        Action::make('lihat_ktp')
                            ->label('Lihat Foto KTP')
                            ->icon('heroicon-o-eye')
                            ->color('gray')
                            ->url(fn (?Host $record): ?string => $record?->ktp_path)
                            ->openUrlInNewTab()
                            ->visible(fn (?Host $record): bool => filled($record?->ktp_path))
                            ->columnSpanFull(),

                        Select::make('ktp_status')
                            ->label('Status KTP')
                            ->options([
                                'unverified' => 'Belum Diajukan',
                                'pending' => 'Menunggu Review',
                                'verified' => 'Terverifikasi',
                                'rejected' => 'Ditolak',
                            ])
                            ->default('unverified')
                            ->required()
                            ->native(false)
                            ->live(),

                        Textarea::make('ktp_rejection_note')
                            ->label('Alasan Penolakan')
                            ->visible(fn (Get $get): bool => $get('ktp_status') === 'rejected')
                            ->columnSpanFull(),
                    ]),

                Section::make('Status Akun')
                    ->columns(2)
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Akun Aktif')
                            ->required(),

                        Toggle::make('is_verified')
                            ->label('Host Terverifikasi')
                            ->required(),

                        TextInput::make('rating_avg')
                            ->label('Rating')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('total_reviews')
                            ->label('Jumlah Review')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(false),
                    ]),

                Section::make('Informasi Bank')
                    ->columns(3)
                    ->schema([
                        TextInput::make('bank_name')
                            ->label('Nama Bank'),

                        TextInput::make('bank_account_name')
                            ->label('Nama Pemilik Rekening'),

                        TextInput::make('bank_account_number')
                            ->label('No. Rekening'),
                    ]),
            ]);
    }
}
