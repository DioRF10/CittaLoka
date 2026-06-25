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

                        TextInput::make('phone_number')
                            ->label('No. Telepon')
                            ->disabled(),

                        TextInput::make('age')
                            ->label('Umur')
                            ->numeric()
                            ->disabled(),

                        TextInput::make('language_preference')
                            ->label('Preferensi Bahasa')
                            ->disabled(),

                        TextInput::make('village')
                            ->label('Desa / Kampung'),

                        TextInput::make('video_url')
                            ->label('URL Video Profil')
                            ->url(),

                        Textarea::make('bio')
                            ->label('Bio Singkat')
                            ->columnSpanFull(),

                        Textarea::make('story')
                            ->label('Cerita Host (Story)')
                            ->columnSpanFull(),

                        \Filament\Forms\Components\TagsInput::make('expertise')
                            ->label('Keahlian (Expertise)')
                            ->disabled()
                            ->columnSpanFull(),
                    ]),

                Section::make('Verifikasi KTP')
                    ->columns(2)
                    ->schema([
                        \Filament\Forms\Components\FileUpload::make('ktp_path')
                            ->label('Foto KTP')
                            ->image()
                            ->disk('public')
                            ->downloadable()
                            ->openable()
                            ->disabled(), // Supaya admin tidak bisa menghapus/mengubah dari sini tanpa sengaja

                        \Filament\Forms\Components\FileUpload::make('ktp_selfie_path')
                            ->label('Selfie dengan KTP')
                            ->image()
                            ->disk('public')
                            ->downloadable()
                            ->openable()
                            ->disabled(),

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

                        TextInput::make('bank_account_holder')
                            ->label('Nama Pemilik (dari Xendit)')
                            ->disabled()
                            ->dehydrated(false)
                            ->helperText('Hasil pengecekan otomatis ke bank, buat dibandingkan sama nama host.'),

                        Select::make('bank_review_status')
                            ->label('Status Verifikasi')
                            ->options([
                                'not_verified' => 'Belum Verifikasi',
                                'verified' => 'Terverifikasi',
                                'needs_review' => 'Perlu Direview',
                            ])
                            ->native(false),

                        Textarea::make('bank_review_note')
                            ->label('Catatan Review')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
