<?php

namespace App\Filament\Resources\SeasonalEvents;

use App\Filament\Resources\SeasonalEvents\Pages\CreateSeasonalEvent;
use App\Filament\Resources\SeasonalEvents\Pages\EditSeasonalEvent;
use App\Filament\Resources\SeasonalEvents\Pages\ListSeasonalEvents;
use App\Models\SeasonalEvent;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class SeasonalEventResource extends Resource
{
    protected static ?string $model = SeasonalEvent::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static string|UnitEnum|null $navigationGroup = 'Konten';

    protected static ?string $navigationLabel = 'Seasonal Calendar';

    protected static ?string $slug = 'seasonal-events';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Event')
                ->columns(2)
                ->components([
                    TextInput::make('nama.id')
                        ->label('Nama (Indonesia)')
                        ->required(),
                    TextInput::make('nama.en')
                        ->label('Nama (English)')
                        ->required(),

                    Textarea::make('deskripsi.id')
                        ->label('Deskripsi (Indonesia)')
                        ->rows(5)
                        ->columnSpan(1),
                    Textarea::make('deskripsi.en')
                        ->label('Deskripsi (English)')
                        ->rows(5)
                        ->columnSpan(1),

                    DatePicker::make('start_date')
                        ->label('Tanggal Mulai')
                        ->required(),
                    DatePicker::make('end_date')
                        ->label('Tanggal Selesai')
                        ->helperText('Kosongkan jika event hanya 1 hari'),

                    TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->unique(SeasonalEvent::class, 'slug', ignoreRecord: true)
                        ->helperText('Contoh: galungan-2026, musim-panen')
                        ->columnSpanFull(),

                    TextInput::make('area')
                        ->label('Area / Lokasi')
                        ->helperText('Contoh: Bali Selatan, Ubud, Seluruh Bali')
                        ->columnSpanFull(),

                    TextInput::make('thumbnail_url')
                        ->label('URL Foto Cover')
                        ->url()
                        ->helperText('Paste URL gambar dari Cloudinary atau sumber lain')
                        ->columnSpanFull(),

                    Toggle::make('is_recurring')
                        ->label('Event Tahunan (berulang tiap tahun)')
                        ->default(true),
                    Toggle::make('is_active')
                        ->label('Aktif Ditampilkan')
                        ->default(true),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama.id')
                    ->label('Nama Event')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('start_date')
                    ->label('Tanggal Mulai')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('end_date')
                    ->label('Tanggal Selesai')
                    ->date('d M Y')
                    ->placeholder('1 hari')
                    ->toggleable(),

                TextColumn::make('area')
                    ->label('Area')
                    ->placeholder('-')
                    ->toggleable(),

                IconColumn::make('is_recurring')
                    ->label('Tahunan')
                    ->boolean(),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->defaultSort('start_date', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListSeasonalEvents::route('/'),
            'create' => CreateSeasonalEvent::route('/create'),
            'edit'   => EditSeasonalEvent::route('/{record}/edit'),
        ];
    }
}
