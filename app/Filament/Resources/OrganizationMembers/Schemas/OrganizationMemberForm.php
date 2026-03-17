<?php

namespace App\Filament\Resources\OrganizationMembers\Schemas;

use App\Models\OrganizationMember;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class OrganizationMemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                //
                Section::make('Informasi Dasar')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),

                        Select::make('position_id')
                            ->label('Jabatan')
                            ->relationship('position', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('Nama Jabatan Baru')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('level')
                                    ->label('Level Hirarki (optional)')
                                    ->numeric()
                                    ->default(0),
                            ]),

                        Select::make('departement_id')
                            ->label('Bidang / Unit')
                            ->relationship('departement', 'name')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('Nama Bidang Baru')
                                    ->required()
                                    ->maxLength(255),
                            ]),

                        Select::make('parent_id')
                            ->label('Atasan Langsung')
                            ->relationship('parent', 'name', modifyQueryUsing: fn(Builder $query, ?OrganizationMember $record) => $record ? $query->where('id', '!=', $record->id) : $query)
                            ->searchable()
                            ->preload(),

                        RichEditor::make('bio')
                            ->columnSpanFull()
                    ])->columns(2),

                Section::make('Media & Status')
                    ->schema([
                        FileUpload::make('photo')
                            ->label('Photo profile')
                            ->image()
                            ->disk('public')
                            ->directory('organization-photos'),

                        Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true),
                    ]),
            ]);
    }
}
