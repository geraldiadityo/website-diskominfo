<?php

namespace App\Filament\Resources\Announcements\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AnnouncementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Detail Pengumuman')
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul Pengumuman')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('url')
                            ->label('URL Redirect')
                            ->url()
                            ->nullable()
                            ->maxLength(255)
                            ->helperText('Opsional. Jika diisi, pengunjung akan diarahkan ke URL ini saat mengklik pengumuman.'),

                        Select::make('file_type')
                            ->label('Tipe File')
                            ->options([
                                'image' => 'Gambar',
                                'pdf' => 'PDF',
                            ])
                            ->required()
                            ->default('image')
                            ->live(),

                        FileUpload::make('file_path')
                            ->label('File Pengumuman')
                            ->required()
                            ->disk('public')
                            ->directory('announcements')
                            ->preserveFilenames()
                            ->acceptedFileTypes([
                                'image/jpeg',
                                'image/png',
                                'image/webp',
                                'image/gif',
                                'application/pdf',
                            ])
                            ->maxSize(10240)
                            ->helperText('Maksimal 10MB. Upload gambar (JPG, PNG, WebP) atau PDF.'),

                        TextInput::make('sort_order')
                            ->label('Urutan Tampil')
                            ->numeric()
                            ->default(0)
                            ->helperText('Angka lebih kecil tampil lebih dulu'),

                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ])->columns(2),
            ]);
    }
}
