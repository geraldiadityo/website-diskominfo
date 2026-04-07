<?php

namespace App\Livewire\Pages;

use App\Repositories\Contracts\SiteSettingRepositoryInterface;
use Livewire\Component;

class ProfilePage extends Component
{
    public function render(SiteSettingRepositoryInterface $settingsRepo)
    {
        $profileData = $settingsRepo->getMultiple([
            'visi' => '',
            'misi' => '',
            'sejarah' => '',
            'tupoksi' => '',
        ]);

        return view('livewire.pages.profile-page', $profileData)
            ->layout('components.layouts.public', ['title' => 'Profil & Visi Misi']);
    }
}
