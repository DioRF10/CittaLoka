<?php

namespace App\Filament\Resources\Experiences\Schemas;

use App\Models\Experience;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ExperienceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Experience (dari Host)')
                    ->description('Konten ini diisi oleh host. Review saja, jangan diubah kecuali untuk koreksi mendesak.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('judul_display')
                            ->label('Judul')
                            ->disabled()
                            ->dehydrated(false)
                            ->afterStateHydrated(fn ($component, ?Experience $record) => $component->state($record?->getJudul())),

                        TextInput::make('host_display')
                            ->label('Host')
                            ->disabled()
                            ->dehydrated(false)
                            ->afterStateHydrated(fn ($component, ?Experience $record) => $component->state($record?->host?->user?->name)),

                        TextInput::make('kategori.slug')
                            ->label('Kategori')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('kabupaten')
                            ->label('Kabupaten')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('harga')
                            ->label('Harga')
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('durasi_menit')
                            ->label('Durasi (menit)')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('kapasitas_min')
                            ->label('Kapasitas Min')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('kapasitas_max')
                            ->label('Kapasitas Max')
                            ->disabled()
                            ->dehydrated(false),

                        Textarea::make('deskripsi_display')
                            ->label('Deskripsi')
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull()
                            ->afterStateHydrated(fn ($component, ?Experience $record) => $component->state($record?->getDeskripsi())),

                        Textarea::make('lokasi_nama')
                            ->label('Lokasi')
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull(),

                        Textarea::make('alamat_lengkap')
                            ->label('Alamat Lengkap')
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull(),

                        Textarea::make('meeting_point')
                            ->label('Meeting Point')
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull(),

                        Textarea::make('cancellation_policy')
                            ->label('Kebijakan Pembatalan')
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull(),

                        Toggle::make('is_indoor')
                            ->label('Indoor')
                            ->disabled()
                            ->dehydrated(false),
                    ]),

                Section::make('Moderasi')
                    ->columns(2)
                    ->schema([
                        Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'pending_review' => 'Menunggu Review',
                                'active' => 'Aktif',
                                'inactive' => 'Nonaktif',
                                'rejected' => 'Ditolak',
                                'archived' => 'Diarsipkan',
                            ])
                            ->default('draft')
                            ->required()
                            ->native(false),

                        Toggle::make('is_featured')
                            ->label('Tampilkan sebagai Featured')
                            ->required(),

                        Toggle::make('is_seasonal')
                            ->label('Experience Musiman')
                            ->required(),

                        Textarea::make('admin_note')
                            ->label('Catatan Admin')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
