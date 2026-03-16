<?php

namespace App\Filament\Resources\Publications\Tables;

use App\Enums\PublicationStatus;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class PublicationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->description(fn($record) => $record->slug),

                TextColumn::make('tipe.name')
                    ->label('Tipe')
                    ->sortable(),

                TextColumn::make('file_type')
                    ->label('Format')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn($state) => strtoupper($state)),

                TextColumn::make('file_size')
                    ->label('Ukuran')
                    ->formatStateUsing(fn($state) => number_format($state / 1024 / 1024, 2) . 'MB')
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->sortable(),

                TextColumn::make('download_count')
                    ->label('Diunduh')
                    ->sortable()
                    ->numeric(),
            ])
            ->filters([
                //
                SelectFilter::make('status')
                    ->options(PublicationStatus::class),
                SelectFilter::make('tipe_id')
                    ->relationship('tipe', 'name')
                    ->label('Tipe'),
            ])
            ->recordActions([
                Action::make('download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->url(fn($record) => Storage::url($record->file_path))
                    ->openUrlInNewTab(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
