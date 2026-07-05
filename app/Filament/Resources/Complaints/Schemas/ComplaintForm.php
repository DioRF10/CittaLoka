<?php

namespace App\Filament\Resources\Complaints\Schemas;

use App\Models\Complaint;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ComplaintForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Complaint')
                    ->columns(2)
                    ->schema([
                        TextInput::make('booking.kode_booking')
                            ->label('Kode Booking')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('filedBy.name')
                            ->label('Diajukan Oleh')
                            ->disabled()
                            ->dehydrated(false)
                            ->afterStateHydrated(fn ($component, ?Complaint $record) => $component->state(
                                $record ? $record->filedBy->name . ' (' . $record->getFiledByRoleLabel() . ')' : null
                            )),

                        TextInput::make('category_display')
                            ->label('Kategori')
                            ->disabled()
                            ->dehydrated(false)
                            ->afterStateHydrated(fn ($component, ?Complaint $record) => $component->state($record?->getCategoryLabel())),

                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull(),
                    ]),

                Section::make('Penyelesaian')
                    ->columns(2)
                    ->schema([
                        Select::make('status')
                            ->options([
                                'pending' => 'Menunggu Ditinjau',
                                'in_review' => 'Sedang Ditinjau',
                                'resolved' => 'Terselesaikan',
                                'dismissed' => 'Ditolak',
                            ])
                            ->required()
                            ->native(false),

                        Textarea::make('resolution_notes')
                            ->label('Catatan Penyelesaian')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
