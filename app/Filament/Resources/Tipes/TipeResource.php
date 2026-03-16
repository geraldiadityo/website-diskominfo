<?php

namespace App\Filament\Resources\Tipes;

use App\Filament\Resources\Tipes\Pages\CreateTipe;
use App\Filament\Resources\Tipes\Pages\EditTipe;
use App\Filament\Resources\Tipes\Pages\ListTipes;
use App\Filament\Resources\Tipes\Schemas\TipeForm;
use App\Filament\Resources\Tipes\Tables\TipesTable;
use App\Models\Tipe;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TipeResource extends Resource
{
    protected static ?string $model = Tipe::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboard;

    protected static string|UnitEnum|null $navigationGroup = 'Publication';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Tipe Document';

    public static function form(Schema $schema): Schema
    {
        return TipeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TipesTable::configure($table);
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
            'index' => ListTipes::route('/'),
            // 'create' => CreateTipe::route('/create'),
            // 'edit' => EditTipe::route('/{record}/edit'),
        ];
    }
}
