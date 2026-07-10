<?php

namespace App\Filament\Resources\Hosts;

use App\Filament\Resources\Hosts\Pages\EditHost;
use App\Filament\Resources\Hosts\Pages\ListHosts;
use App\Filament\Resources\Hosts\Pages\ViewHost;
use App\Filament\Resources\Hosts\Schemas\HostForm;
use App\Filament\Resources\Hosts\Schemas\HostInfolist;
use App\Filament\Resources\Hosts\Tables\HostsTable;
use App\Models\Host;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class HostResource extends Resource
{
    protected static ?string $model = Host::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-identification';

    protected static string|UnitEnum|null $navigationGroup = 'Manajemen Pengguna';

    protected static ?string $recordTitleAttribute = 'village';

    public static function getGloballySearchableAttributes(): array
    {
        return ['user.name', 'village'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->user->name . ' - ' . $record->village;
    }

    public static function form(Schema $schema): Schema
    {
        return HostForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return HostInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HostsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHosts::route('/'),
            'view' => ViewHost::route('/{record}'),
            'edit' => EditHost::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
