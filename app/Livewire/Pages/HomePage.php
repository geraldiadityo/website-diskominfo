<?php

namespace App\Livewire\Pages;

use App\Repositories\Contracts\ArticleRepositoryInterface;
use App\Repositories\Contracts\OrganizationRepositoryInterface;
use App\Repositories\Contracts\PublicationRepositoryInterface;
use App\Repositories\Contracts\SiteSettingRepositoryInterface;
use Livewire\Component;
use Carbon\Carbon;

class HomePage extends Component
{
    public bool $showPopup = false;
    public ?string $popupImage = null;
    public ?string $popupUrl = null;

    public function mount(SiteSettingRepositoryInterface $settingsRepo)
    {
        $popupActive = $settingsRepo->get('popup_active', false);
        $this->popupImage = $settingsRepo->get('popup_image');
        $this->popupUrl = $settingsRepo->get('popup_url');
        $popupEndDate = $settingsRepo->get('popup_end_date');

        $isExpired = $popupEndDate ? Carbon::parse($popupEndDate)->isPast() : false;

        // Tampilkan hanya jika: Aktif, Gambar ada, Belum expired, DAN BELUM pernah ditutup (Session)
        if (
            filter_var($popupActive, FILTER_VALIDATE_BOOLEAN) &&
            $this->popupImage &&
            !$isExpired //&&
            // !session()->has('popup_dismissed')
        ) {
            $this->showPopup = true;
        }
    }

    public function closePopup()
    {
        // Simpan status ditutup ke session agar tidak muncul lagi saat pindah halaman
        session()->put('popup_dismissed', true);
        $this->showPopup = false;
    }

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
            'leader_profile_description' => $settingsRepo->get('leader_profile_description', 'Di bawah kepemimpinan yang progresif...'),
        ])->layout('components.layouts.public', ['title' => 'Beranda']);
    }
}
