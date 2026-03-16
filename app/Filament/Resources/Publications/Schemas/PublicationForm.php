<?php

namespace App\Filament\Resources\Publications\Schemas;

use App\Enums\PublicationStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PublicationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                //
                Section::make('Informasi Publikasi')
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul Publikasi')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),

                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true),

                        Select::make('tipe_id')
                            ->label('Tipe')
                            ->relationship('tipe', 'name')
                            ->searchable()
                            ->preload(),

                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(4)
                            ->columnSpanFull()
                    ])->columns(2),

                Section::make('Dokument & Status')
                    ->schema([
                        FileUpload::make('file_path')
                            ->label('File dokument')
                            ->disk('public')
                            ->directory('publications')
                            ->acceptedFileTypes([
                                'application/pdf',
                                'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                'application/vnd.ms-excel',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                            ])
                            ->required()
                            ->columnSpanFull(),

                        Select::make('status')
                            ->options(PublicationStatus::class)
                            ->default('draf')
                            ->required(),

                        DateTimePicker::make('published_at')
                            ->label('waktu publish'),
                    ])->columns(2)
            ]);
    }
}
