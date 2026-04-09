<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use BackedEnum;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Storage;
use UnitEnum;

class ManageSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::Cog6Tooth;

    protected static string|UnitEnum|null $navigationGroup = 'System Management';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Site Setting';

    protected static ?string $title = 'Site Setting';

    protected string $view = 'filament.pages.manage-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $setting = SiteSetting::pluck('value', 'key')->map(function ($value) {
            $decoded = json_decode($value, true);

            return json_last_error() === JSON_ERROR_NONE && is_array($decoded) ? $decoded : $value;
        })->toArray();
        $this->form->fill($setting);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->columns(1)
            ->components([
                Tabs::make('settings')
                    ->tabs([
                        Tab::make('General')
                            ->icon(Heroicon::GlobeAlt)
                            ->schema([
                                Section::make('Informasi Dasar')
                                    ->schema([
                                        TextInput::make('site_name')
                                            ->required(),
                                        Textarea::make('site_description')
                                            ->label('Deskripsi Situs')
                                            ->rows(3),
                                        FileUpload::make('site_logo')
                                            ->label('Logo Situs')
                                            ->image()
                                            ->disk('public')
                                            ->directory('settings')
                                            ->preserveFilenames(),
                                        FileUpload::make('site_favicon')
                                            ->label('Favicon')
                                            ->image()
                                            ->disk('public')
                                            ->directory('settings'),
                                        FileUpload::make('hero_image')
                                            ->label('Gambar Hero Homepage')
                                            ->helperText('Gambar latar belakang jumbotron di halaman utama. Ukuran rekomendasi: 1920x800px')
                                            ->image()
                                            ->disk('public')
                                            ->directory('settings')
                                            ->preserveFilenames(),
                                    ])->columns(2),
                                Section::make('Profil Pimpinan (Beranda)')
                                    ->description('Teks yang tampil di bagian Profil Pimpinan pada halaman Beranda')
                                    ->schema([
                                        TextInput::make('leader_profile_subtitle')
                                            ->label('Label Kecil (Subtitle)')
                                            ->placeholder('Profil Pimpinan'),
                                        TextInput::make('leader_profile_title')
                                            ->label('Judul Utama')
                                            ->placeholder('Mendorong Inovasi Melalui Sinergi Digital'),
                                        Textarea::make('leader_profile_description')
                                            ->label('Deskripsi')
                                            ->rows(3)
                                            ->placeholder('Di bawah kepemimpinan yang progresif...'),
                                    ]),
                            ]),
                        Tab::make('Contact')
                            ->icon(Heroicon::Phone)
                            ->schema([
                                Section::make('Kontak dan Alamat')
                                    ->schema([
                                        TextInput::make('contact_email')
                                            ->label('Email Kontak')
                                            ->email(),
                                        TextInput::make('contact_phone')
                                            ->label('Nomor Telp')
                                            ->tel(),
                                        Textarea::make('contact_address')
                                            ->label('Alamat Lengkap')
                                            ->columnSpanFull(),
                                        Repeater::make('operational_hours')
                                            ->label('Jam Operasional')
                                            ->schema([
                                                TextInput::make('day')
                                                    ->label('Hari')
                                                    ->placeholder('Senin - Kamis')
                                                    ->required(),
                                                TextInput::make('time')
                                                    ->label('Jam')
                                                    ->placeholder('07:30 - 16:00')
                                                    ->required(),
                                            ])
                                            ->columns(2)
                                            ->columnSpanFull()
                                            ->defaultItems(0)
                                            ->addActionLabel('Tambah Jam Operasional'),
                                    ])->columns(2),
                            ]),
                        Tab::make('Social Media')
                            ->icon(Heroicon::Share)
                            ->schema([
                                Section::make('Tautan Sosial Media')
                                    ->schema([
                                        TextInput::make('social_facebook')
                                            ->label('Facebook URL')
                                            ->url(),
                                        TextInput::make('social_instagram')
                                            ->label('Instagram URL')
                                            ->url(),
                                    ])->columns(2),
                            ]),
                        Tab::make('Profile')
                            ->icon(Heroicon::BuildingOffice2)
                            ->schema([
                                Section::make('Konten Profil Organisasi')
                                    ->description('Kelola informasi profil yang tampil di halaman publik')
                                    ->schema([
                                        RichEditor::make('visi')
                                            ->label('Visi')
                                            ->columnSpanFull(),
                                        RichEditor::make('misi')
                                            ->label('Misi')
                                            ->columnSpanFull(),
                                        RichEditor::make('sejarah')
                                            ->label('Sejarah')
                                            ->columnSpanFull(),
                                        RichEditor::make('tupoksi')
                                            ->label('Tugas Pokok & Fungsi')
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                        Tab::make('Jumbotron')
                            ->icon(Heroicon::Photo)
                            ->schema([
                                Section::make('Hero Homepage')
                                    ->description('Teks utama yang tampil di jumbotron halaman beranda')
                                    ->schema([
                                        TextInput::make('hero_title')
                                            ->label('Judul Hero')
                                            ->placeholder('Mewujudkan Transformasi Digital Daerah'),
                                        TextInput::make('hero_subtitle')
                                            ->label('Subtitle Hero')
                                            ->placeholder('Membangun ekosistem teknologi informasi yang inklusif...'),
                                        TextInput::make('hero_label')
                                            ->label('Label Kecil di Atas Judul')
                                            ->placeholder('Dinas Komunikasi dan Informatika'),
                                    ])->columns(1),
                                Section::make('Header Halaman Dalam')
                                    ->description('Teks judul dan deskripsi pada jumbotron setiap halaman')
                                    ->schema([
                                        TextInput::make('jumbotron_berita_title')
                                            ->label('Judul — Halaman Berita')
                                            ->placeholder('Berita Terbaru'),
                                        TextInput::make('jumbotron_berita_subtitle')
                                            ->label('Subtitle — Halaman Berita')
                                            ->placeholder('Pusat Informasi'),
                                        TextInput::make('jumbotron_publikasi_title')
                                            ->label('Judul — Halaman Publikasi')
                                            ->placeholder('Publikasi & Dokumen'),
                                        TextInput::make('jumbotron_publikasi_subtitle')
                                            ->label('Subtitle — Halaman Publikasi')
                                            ->placeholder('Transparansi Informasi'),
                                        TextInput::make('jumbotron_publikasi_desc')
                                            ->label('Deskripsi — Halaman Publikasi')
                                            ->placeholder('Akses dokumen resmi, laporan tahunan...'),
                                        TextInput::make('jumbotron_kontak_title')
                                            ->label('Judul — Halaman Kontak')
                                            ->placeholder('Kontak & Bantuan'),
                                        TextInput::make('jumbotron_kontak_subtitle')
                                            ->label('Subtitle — Halaman Kontak')
                                            ->placeholder('Hubungi Kami'),
                                        TextInput::make('jumbotron_organisasi_title')
                                            ->label('Judul — Halaman Struktur Organisasi')
                                            ->placeholder('Struktur Organisasi'),
                                        TextInput::make('jumbotron_organisasi_subtitle')
                                            ->label('Subtitle — Halaman Struktur Organisasi')
                                            ->placeholder('Tentang Kami'),
                                        TextInput::make('jumbotron_profil_title')
                                            ->label('Judul — Halaman Profil')
                                            ->placeholder('Profil & Visi Misi'),
                                        TextInput::make('jumbotron_profil_subtitle')
                                            ->label('Subtitle — Halaman Profil')
                                            ->placeholder('Tentang Diskominfo'),
                                    ])->columns(2),
                            ]),
                        Tab::make('Pop Up')
                            ->icon(Heroicon::Megaphone)
                            ->schema([
                                Section::make('Pengaturan Pop-up Beranda')
                                    ->schema([
                                        Toggle::make('popup_active')
                                            ->label('Aktifkan Pop up')
                                            ->default(false),
                                        DateTimePicker::make('popup_end_date')
                                            ->label('Batas Waktu Tampil (expired)'),
                                        FileUpload::make('popup_image')
                                            ->label('Gambar pop-up')
                                            ->image()
                                            ->disk('public')
                                            ->directory('settings')
                                            ->preserveFilenames(),
                                        TextInput::make('popup_url')
                                            ->label('Url Tujuan (Optional)')
                                            ->url(),
                                    ])->columns(2),
                            ])
                    ]),
            ]);
    }

    public function submit(): void
    {
        $state = $this->form->getState();
        $groups = [
            'site_name' => 'general',
            'site_description' => 'general',
            'site_logo' => 'general',
            'site_favicon' => 'general',
            'hero_image' => 'general',
            'hero_title' => 'jumbotron',
            'hero_subtitle' => 'jumbotron',
            'hero_label' => 'jumbotron',
            'jumbotron_berita_title' => 'jumbotron',
            'jumbotron_berita_subtitle' => 'jumbotron',
            'jumbotron_publikasi_title' => 'jumbotron',
            'jumbotron_publikasi_subtitle' => 'jumbotron',
            'jumbotron_publikasi_desc' => 'jumbotron',
            'jumbotron_kontak_title' => 'jumbotron',
            'jumbotron_kontak_subtitle' => 'jumbotron',
            'jumbotron_organisasi_title' => 'jumbotron',
            'jumbotron_organisasi_subtitle' => 'jumbotron',
            'jumbotron_profil_title' => 'jumbotron',
            'jumbotron_profil_subtitle' => 'jumbotron',
            'contact_email' => 'contact',
            'contact_phone' => 'contact',
            'contact_address' => 'contact',
            'operational_hours' => 'contact',
            'social_facebook' => 'social',
            'social_instagram' => 'social',
            'leader_profile_subtitle' => 'general',
            'leader_profile_title' => 'general',
            'leader_profile_description' => 'general',
            'visi' => 'profile',
            'misi' => 'profile',
            'sejarah' => 'profile',
            'tupoksi' => 'profile',
            'popup_active' => 'popup',
            'popup_image' => 'popup',
            'popup_end_date' => 'popup',
            'popup_url' => 'popup',
        ];

        $oldImage = SiteSetting::getSetting('popup_image');
        $newImage = $state['popup_image'] ?? null;

        if ($oldImage && $oldImage !== $newImage) {
            if (Storage::disk('public')->exists($oldImage)) {
                Storage::disk('public')->delete($oldImage);
            }
        }

        foreach ($state as $key => $value) {
            SiteSetting::UpdateOrCreate(
                ['key' => $key],
                [
                    'value' => is_array($value) ? json_encode($value) : $value,
                    'group' => $groups[$key] ?? 'general',
                ]
            );
        }

        Notification::make()
            ->title('Berhasil disimpan')
            ->body('Pengaturan berhasil diperbarui')
            ->success()
            ->send();
    }
}
