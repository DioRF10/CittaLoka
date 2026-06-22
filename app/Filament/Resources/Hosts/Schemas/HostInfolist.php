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
                TextEntry::make('user.name')
                    ->label('Nama Host'),
                TextEntry::make('user.email')
                    ->label('Email'),
                TextEntry::make('bio')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('village')
                    ->placeholder('-'),
                TextEntry::make('video_url')
                    ->placeholder('-'),
                ImageEntry::make('ktp_path')
                    ->label('Foto KTP')
                    ->height(250)
                    ->visibility('public')
                    ->placeholder('Belum upload KTP')
                    ->columnSpanFull(),
                TextEntry::make('ktp_status')
                    ->badge(),
                TextEntry::make('ktp_rejection_note')
                    ->placeholder('-')
                    ->columnSpanFull(),
                IconEntry::make('is_active')
                    ->boolean(),
                IconEntry::make('is_verified')
                    ->boolean(),
                TextEntry::make('rating_avg')
                    ->numeric(),
                TextEntry::make('total_reviews')
                    ->numeric(),
                TextEntry::make('bank_name')
                    ->placeholder('-'),
                TextEntry::make('bank_account_name')
                    ->placeholder('-'),
                TextEntry::make('bank_account_number')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Host $record): bool => $record->trashed()),
            ]);
    }
}
