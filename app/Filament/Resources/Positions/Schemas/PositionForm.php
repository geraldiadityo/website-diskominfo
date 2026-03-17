<?php

namespace App\Filament\Resources\Positions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PositionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                //
                Section::make('Jabatan')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Jabatan')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        TextInput::make('level')
                            ->label('Level Hirarki (optional)')
                            ->numeric()
                            ->default(0),
                    ])
            ]);
    }
}
