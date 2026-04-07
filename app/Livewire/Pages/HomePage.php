<?php

namespace App\Livewire\Pages;

use App\Repositories\Contracts\ArticleRepositoryInterface;
use App\Repositories\Contracts\OrganizationRepositoryInterface;
use App\Repositories\Contracts\PublicationRepositoryInterface;
use App\Repositories\Contracts\SiteSettingRepositoryInterface;
use Livewire\Component;

class HomePage extends Component
{
    public function render(
        ArticleRepositoryInterface $articleRepo,
        PublicationRepositoryInterface $publicationRepo,
        OrganizationRepositoryInterface $organizationRepo,
        SiteSettingRepositoryInterface $settingsRepo,
    ) {
        return view('livewire.pages.home-page', [
            'articles' => $articleRepo->getPublished(3),
            'publications' => $publicationRepo->getLatestPublished(4),
            'leader' => $organizationRepo->getLeader(),
            'heroImage' => $settingsRepo->get('hero_image'),
            'leader_profile_subtitle' => $settingsRepo->get('leader_profile_subtitle', 'Profil Pimpinan'),
            'leader_profile_title' => $settingsRepo->get('leader_profile_title', 'Mendorong Inovasi Melalui Sinergi Digital'),
            'leader_profile_description' => $settingsRepo->get('leader_profile_description', 'Di bawah kepemimpinan yang progresif, Diskominfo berkomitmen untuk menjadi motor penggerak digitalisasi daerah, memastikan setiap warga memiliki akses yang sama terhadap informasi dan layanan pemerintah.'),
        ])->layout('components.layouts.public', ['title' => 'Beranda']);
    }
}
