<?php

namespace App\Repositories;

use App\Models\SiteSetting;
use App\Repositories\Contracts\SiteSettingRepositoryInterface;

class SiteSettingRepository implements SiteSettingRepositoryInterface
{
    public function get(string $key, mixed $default = null): mixed
    {
        return SiteSetting::getSetting($key, $default);
    }

    public function getMultiple(array $keys): array
    {
        $result = [];

        foreach ($keys as $key => $default) {
            $result[$key] = $this->get($key, $default);
        }

        return $result;
    }

    public function getSiteName(): string
    {
        return $this->get('site_name', 'Diskominfo');
    }

    public function getLogo(): ?string
    {
        return $this->get('site_logo');
    }

    public function getContact(): array
    {
        return [
            'email' => $this->get('contact_email', 'info@diskominfo.go.id'),
            'phone' => $this->get('contact_phone', '(021) 1234-5678'),
            'address' => $this->get('contact_address', ''),
        ];
    }

    public function getSocial(): array
    {
        return [
            'facebook' => $this->get('social_facebook'),
            'instagram' => $this->get('social_instagram'),
        ];
    }

    public function getJumbotron(): array
    {
        return $this->getMultiple([
            'hero_title' => 'Mewujudkan Transformasi Digital Daerah',
            'hero_subtitle' => 'Membangun ekosistem teknologi informasi yang inklusif, aman, dan transparan untuk melayani masyarakat di era digital.',
            'hero_label' => 'Dinas Komunikasi dan Informatika',
            'jumbotron_berita_title' => 'Berita Terbaru',
            'jumbotron_berita_subtitle' => 'Pusat Informasi',
            'jumbotron_publikasi_title' => 'Publikasi & Dokumen',
            'jumbotron_publikasi_subtitle' => 'Transparansi Informasi',
            'jumbotron_publikasi_desc' => 'Akses dokumen resmi, laporan tahunan, dan regulasi terkini terkait kebijakan komunikasi dan informatika daerah.',
            'jumbotron_kontak_title' => 'Kontak & Bantuan',
            'jumbotron_kontak_subtitle' => 'Hubungi Kami',
            'jumbotron_organisasi_title' => 'Struktur Organisasi',
            'jumbotron_organisasi_subtitle' => 'Tentang Kami',
            'jumbotron_profil_title' => 'Profil & Visi Misi',
            'jumbotron_profil_subtitle' => 'Tentang Diskominfo',
        ]);
    }
}
