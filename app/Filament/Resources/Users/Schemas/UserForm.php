<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                //
                Section::make('User Details')->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),

                    Select::make('role')
                        ->options([
                            'admin' => 'Administrator',
                            'publisher' => 'Publisher',
                            'author' => 'Penulis'
                        ])
                        ->required()
                        ->native(false),

                    TextInput::make('password')
                        ->password()
                        ->dehydrateStateUsing(fn($state) => $state)
                        ->dehydrated(fn($state) => filled($state))
                        ->required(fn(string $context): bool => $context === 'create')
                        ->maxLength(255)
                ])->columns(2),
            ]);
    }
}
