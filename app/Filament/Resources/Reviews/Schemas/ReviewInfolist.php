<?php

namespace App\Filament\Resources\Reviews\Schemas;

use App\Models\Review;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ReviewInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('booking.kode_booking')
                    ->label('Kode Booking'),
                TextEntry::make('user.name')
                    ->label('Ditulis oleh'),
                TextEntry::make('experience.judul')
                    ->label('Experience')
                    ->getStateUsing(fn (Review $record): string => $record->experience?->getJudul() ?? '-'),
                TextEntry::make('host.user.name')
                    ->label('Host'),
                TextEntry::make('rating')
                    ->label('Rating')
                    ->suffix(' / 5'),
                TextEntry::make('text')
                    ->label('Isi Review')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('admin_note')
                    ->label('Catatan Admin')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('published_at')
                    ->label('Tayang Pada')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Review $record): bool => $record->trashed()),
            ]);
    }
}
