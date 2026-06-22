<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\SoulType;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Dasar')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama')
                            ->required(),

                        TextInput::make('username'),

                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required(),

                        TextInput::make('phone_number')
                            ->label('No. HP')
                            ->tel(),

                        DatePicker::make('tanggal_lahir')
                            ->label('Tanggal Lahir'),

                        Select::make('jenis_kelamin')
                            ->label('Jenis Kelamin')
                            ->options(['Pria' => 'Pria', 'Wanita' => 'Wanita'])
                            ->native(false),
                    ]),

                Section::make('Akun & Role')
                    ->columns(2)
                    ->schema([
                        Select::make('role')
                            ->options([
                                'admin' => 'Admin',
                                'user' => 'Traveler',
                                'host' => 'Host',
                            ])
                            ->default('user')
                            ->required()
                            ->native(false),

                        Select::make('soul_type_id')
                            ->label('Soul Type')
                            ->options(
                                SoulType::query()->get()->mapWithKeys(
                                    fn (SoulType $type) => [$type->id => $type->getNama()]
                                )
                            )
                            ->searchable()
                            ->native(false),

                        TextInput::make('password')
                            ->password()
                            ->revealable()
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null)
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->helperText('Kosongkan kalau tidak ingin mengubah password.'),

                        TextInput::make('preferred_currency')
                            ->label('Mata Uang')
                            ->required()
                            ->default('IDR'),
                    ]),

                Section::make('Lainnya')
                    ->description('Data ini diisi otomatis oleh sistem.')
                    ->columns(2)
                    ->collapsed()
                    ->schema([
                        TextInput::make('country_code')
                            ->label('Kode Negara')
                            ->disabled(),

                        TextInput::make('locale')
                            ->disabled(),

                        TextInput::make('avatar')
                            ->disabled(),

                        TextInput::make('google_id')
                            ->disabled(),

                        DateTimePicker::make('terms_accepted_at')
                            ->label('Setuju Terms Pada')
                            ->disabled(),

                        DateTimePicker::make('privacy_accepted_at')
                            ->label('Setuju Privacy Pada')
                            ->disabled(),

                        DateTimePicker::make('email_verified_at')
                            ->label('Email Diverifikasi Pada'),

                        DateTimePicker::make('last_login_at')
                            ->label('Login Terakhir')
                            ->disabled(),

                        DateTimePicker::make('onboarding_completed_at')
                            ->label('Onboarding Selesai Pada')
                            ->disabled(),
                    ]),
            ]);
    }
}
