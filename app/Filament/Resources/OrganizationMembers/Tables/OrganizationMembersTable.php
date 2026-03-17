<?php

namespace App\Filament\Resources\OrganizationMembers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrganizationMembersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                //
                ImageColumn::make('photo')
                    ->disk('public')
                    ->circular(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('position.name')
                    ->label('Jabatan')
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('departement.name')
                    ->label('Bidang / Unit')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('parent.name')
                    ->label('Atasan')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->filters([
                //
                SelectFilter::make('departement_id')
                    ->label('Filter Bidang')
                    ->relationship('departement', 'name'),

                SelectFilter::make('position_id')
                    ->label('Filter Jabatan')
                    ->relationship('position', 'name'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
