<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('username')
                    ->placeholder('-'),
                TextEntry::make('email')
                    ->label('Email address'),
                TextEntry::make('phone_number')
                    ->placeholder('-'),
                TextEntry::make('country_code')
                    ->placeholder('-'),
                TextEntry::make('tanggal_lahir')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('jenis_kelamin')
                    ->badge()
                    ->placeholder('-'),
                TextEntry::make('locale')
                    ->placeholder('-'),
                TextEntry::make('avatar')
                    ->placeholder('-'),
                TextEntry::make('google_id')
                    ->placeholder('-'),
                TextEntry::make('soul_type_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('preferred_currency'),
                TextEntry::make('terms_accepted_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('privacy_accepted_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('email_verified_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('last_login_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('onboarding_completed_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('role')
                    ->badge(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (User $record): bool => $record->trashed()),
            ]);
    }
}
