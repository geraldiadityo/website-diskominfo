<?php

namespace App\Filament\Resources\Articles\Schemas;

use App\Enums\ArticleStatus;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                //
                Grid::make(3)
                    ->schema([
                        Section::make('Kontent Utama')
                            ->columnSpan(2)
                            ->schema([
                                TextInput::make('title')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),
                                TextInput::make('slug')
                                    ->required()
                                    ->unique(ignoreRecord: true),
                                RichEditor::make('content')
                                    ->required()
                                    ->columnSpan('full')
                            ]),
                        Section::make('Meta & Publikasi')
                            ->schema([
                                FileUpload::make('featured Image')
                                    ->image()
                                    ->disk('public')
                                    ->directory('articles')
                                    ->preserveFilenames()
                                    ->imageEditor(),

                                Select::make('category_id')
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload(),

                                Select::make('status')
                                    ->options(ArticleStatus::class)
                                    ->required()
                                    ->disableOptionWhen(fn(string $value): bool => Auth::user()->role === 'author' && in_array($value, [ArticleStatus::PUBLISH->value, ArticleStatus::ARCHIVED->value])),

                                Select::make('tags')
                                    ->relationship('tags', 'name')
                                    ->multiple()
                                    ->preload()
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->required(),
                                        TextInput::make('slug')
                                            ->required(),
                                    ]),
                            ]),
                    ]),
                Section::make('Search Engine Optimization (SEO)')
                    ->description('Atur bagaimana artikel ini tampil di google search engine')
                    ->schema([
                        TextInput::make('seo_title')
                            ->label('Meta title')
                            ->placeholder('Judul Menarik untuk google')
                            ->maxLength(60)
                            ->helperText('Biarkan kosong untuk menggunakan judul artikel asli'),

                        Textarea::make('seo_description')
                            ->label('Meta description')
                            ->placeholder('Ringkasan singkat yang muncul dibawah judul hasil pencarian')
                            ->rows(2)
                            ->maxLength(160)
                    ])->collapsed()
            ]);
    }
}
