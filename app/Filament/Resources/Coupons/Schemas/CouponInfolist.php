<?php

namespace App\Filament\Resources\Coupons\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CouponInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('code')
                    ->label('Kode'),
                TextEntry::make('discount_type')
                    ->label('Tipe Diskon')
                    ->badge(),
                TextEntry::make('discount_value')
                    ->label('Nilai Diskon'),
                TextEntry::make('min_order')
                    ->label('Minimal Order')
                    ->money('IDR'),
                TextEntry::make('max_discount')
                    ->label('Maksimal Diskon')
                    ->money('IDR')
                    ->placeholder('-'),
                TextEntry::make('max_usage')
                    ->label('Maksimal Pemakaian')
                    ->placeholder('Tidak dibatasi'),
                TextEntry::make('used_count')
                    ->label('Sudah Dipakai'),
                IconEntry::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                TextEntry::make('expired_at')
                    ->label('Kedaluwarsa Pada')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime(),
            ]);
    }
}
