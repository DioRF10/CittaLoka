<?php

namespace App\Filament\Resources\Experiences\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ExperienceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                RepeatableEntry::make('photos')
                    ->label('Galeri Foto')
                    ->schema([
                        ImageEntry::make('url')
                            ->label('')
                            ->visibility('public')
                            ->height(150),
                    ])
                    ->grid(4)
                    ->columnSpanFull(),

                TextEntry::make('judul')
                    ->label('Judul')
                    ->formatStateUsing(function ($state): string {
                        if (is_string($state)) {
                            $state = json_decode($state, true);
                        }

                        return is_array($state) ? ($state['id'] ?? $state['en'] ?? '-') : '-';
                    }),
                TextEntry::make('deskripsi')
                    ->label('Deskripsi')
                    ->formatStateUsing(function ($state): string {
                        if (is_string($state)) {
                            $state = json_decode($state, true);
                        }

                        return is_array($state) ? ($state['id'] ?? $state['en'] ?? '-') : '-';
                    })
                    ->columnSpanFull(),
                TextEntry::make('host.user.name')
                    ->label('Host'),
                TextEntry::make('kategori.nama')
                    ->label('Kategori')
                    ->formatStateUsing(function ($state) {
                        if (is_string($state)) {
                            $state = json_decode($state, true);
                        }

                        return is_array($state) ? ($state['id'] ?? '-') : ($state ?? '-');
                    }),
                TextEntry::make('slug'),
                TextEntry::make('harga')
                    ->numeric(),
                TextEntry::make('durasi_menit')
                    ->numeric(),
                TextEntry::make('kapasitas_min')
                    ->numeric(),
                TextEntry::make('kapasitas_max')
                    ->numeric(),
                TextEntry::make('lokasi_lat')
                    ->numeric(),
                TextEntry::make('lokasi_lng')
                    ->numeric(),
                TextEntry::make('lokasi_nama'),
                TextEntry::make('alamat_lengkap')
                    ->columnSpanFull(),
                TextEntry::make('meeting_point')
                    ->columnSpanFull(),
                TextEntry::make('kabupaten'),
                TextEntry::make('cancellation_policy')
                    ->placeholder('-')
                    ->columnSpanFull(),
                IconEntry::make('is_indoor')
                    ->boolean(),
                IconEntry::make('is_featured')
                    ->boolean(),
                IconEntry::make('is_seasonal')
                    ->boolean(),
                TextEntry::make('rating_avg')
                    ->numeric(),
                TextEntry::make('total_reviews')
                    ->numeric(),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('admin_note')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
