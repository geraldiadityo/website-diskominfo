<?php

namespace App\Filament\Resources\Departements\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DepartementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                //
                Section::make('Unit / Bidang')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->label('Nama Unit / Bidang')
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Textarea::make('description')
                            ->nullable()
                            ->rows(3),
                    ])
            ]);
    }
}
