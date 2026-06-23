<?php

namespace App\Filament\Resources\Coupons\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CouponForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('Kode Kupon')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(50),

                Select::make('discount_type')
                    ->label('Tipe Diskon')
                    ->options([
                        'percentage' => 'Persentase (%)',
                        'fixed' => 'Nominal (Rp)',
                    ])
                    ->required()
                    ->native(false),

                TextInput::make('discount_value')
                    ->label('Nilai Diskon')
                    ->numeric()
                    ->required(),

                TextInput::make('min_order')
                    ->label('Minimal Order')
                    ->prefix('Rp')
                    ->numeric()
                    ->default(0),

                TextInput::make('max_discount')
                    ->label('Maksimal Diskon')
                    ->prefix('Rp')
                    ->numeric()
                    ->helperText('Khusus untuk tipe persentase, biar diskon gak kebesaran.'),

                TextInput::make('max_usage')
                    ->label('Maksimal Pemakaian')
                    ->numeric()
                    ->helperText('Kosongkan kalau gak dibatasi.'),

                TextInput::make('used_count')
                    ->label('Sudah Dipakai')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(false),

                DateTimePicker::make('expired_at')
                    ->label('Kedaluwarsa Pada'),

                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true)
                    ->required(),
            ]);
    }
}
