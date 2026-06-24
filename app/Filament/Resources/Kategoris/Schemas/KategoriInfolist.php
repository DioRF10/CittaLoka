<?php

namespace App\Filament\Resources\Kategoris\Schemas;

use App\Models\Kategori;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class KategoriInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nama_id')
                    ->label('Nama (Indonesia)')
                    ->getStateUsing(fn (Kategori $record): string => $record->getNama('id')),
                TextEntry::make('nama_en')
                    ->label('Nama (English)')
                    ->getStateUsing(fn (Kategori $record): string => $record->getNama('en')),
                TextEntry::make('slug'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
