<?php

namespace App\Livewire\Pages;

use App\Repositories\Contracts\OrganizationRepositoryInterface;
use Livewire\Component;

class OrganizationStructure extends Component
{
    public function render(OrganizationRepositoryInterface $organizationRepo)
    {
        return view('livewire.pages.organization-structure', [
            'members' => $organizationRepo->getHierarchy(),
        ])->layout('components.layouts.public', ['title' => 'Struktur Organisasi']);
    }
}
