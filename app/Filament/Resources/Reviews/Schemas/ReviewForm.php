<?php

namespace App\Filament\Resources\Reviews\Schemas;

use App\Models\Review;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Review')
                    ->description('Diisi oleh traveler, review saja.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('booking.kode_booking')
                            ->label('Kode Booking')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('user.name')
                            ->label('Ditulis oleh')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('experience_display')
                            ->label('Experience')
                            ->disabled()
                            ->dehydrated(false)
                            ->afterStateHydrated(fn ($component, ?Review $record) => $component->state($record?->experience?->getJudul())),

                        TextInput::make('host.user.name')
                            ->label('Host')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('rating')
                            ->label('Rating')
                            ->suffix('/ 5')
                            ->disabled()
                            ->dehydrated(false),

                        Textarea::make('text')
                            ->label('Isi Review')
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull(),
                    ]),

                Section::make('Moderasi')
                    ->columns(2)
                    ->schema([
                        Select::make('status')
                            ->options([
                                'pending' => 'Menunggu Review',
                                'approved' => 'Disetujui',
                                'rejected' => 'Ditolak',
                            ])
                            ->default('pending')
                            ->required()
                            ->native(false),

                        DateTimePicker::make('published_at')
                            ->label('Tayang Pada'),

                        Textarea::make('admin_note')
                            ->label('Catatan Admin')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
