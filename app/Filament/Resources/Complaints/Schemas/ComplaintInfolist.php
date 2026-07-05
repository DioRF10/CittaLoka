<?php

namespace App\Filament\Resources\Complaints\Schemas;

use App\Models\Complaint;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ComplaintInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('booking.kode_booking')
                    ->label('Kode Booking'),

                TextEntry::make('filedBy.name')
                    ->label('Diajukan Oleh')
                    ->formatStateUsing(fn (Complaint $record): string => $record->filedBy->name . ' (' . $record->getFiledByRoleLabel() . ')'),

                TextEntry::make('category')
                    ->label('Kategori')
                    ->formatStateUsing(fn (Complaint $record): string => $record->getCategoryLabel()),

                TextEntry::make('description')
                    ->label('Deskripsi')
                    ->columnSpanFull(),

                TextEntry::make('status')
                    ->badge()
                    ->formatStateUsing(fn (Complaint $record): string => $record->getStatusLabel()),

                TextEntry::make('resolution_notes')
                    ->label('Catatan Penyelesaian')
                    ->placeholder('-')
                    ->columnSpanFull(),

                TextEntry::make('resolvedBy.name')
                    ->label('Diselesaikan Oleh')
                    ->placeholder('-'),

                TextEntry::make('resolved_at')
                    ->label('Diselesaikan Pada')
                    ->dateTime()
                    ->placeholder('-'),

                TextEntry::make('created_at')
                    ->label('Diajukan Pada')
                    ->dateTime(),
            ]);
    }
}
