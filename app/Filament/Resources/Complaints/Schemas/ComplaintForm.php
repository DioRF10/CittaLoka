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

                        \Filament\Forms\Components\Placeholder::make('photos')
                            ->label('Foto Bukti')
                            ->content(fn ($record) => new \Illuminate\Support\HtmlString(
                                $record?->photos->count() > 0
                                    ? '<div style="display:flex; gap:8px; flex-wrap:wrap;">' . $record->photos->map(fn($p) => "<a href='{$p->url}' target='_blank'><img src='{$p->url}' style='width:100px; height:100px; object-fit:cover; border-radius:8px; border:1px solid #ddd;'></a>")->join('') . '</div>'
                                    : '-'
                            ))
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
