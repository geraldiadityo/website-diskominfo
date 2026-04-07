<?php

namespace App\Livewire\Pages;

use App\Enums\PublicationStatus;
use App\Models\Tipe;
use App\Repositories\Contracts\PublicationRepositoryInterface;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PublicationIndex extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'tipe')]
    public string $tipeSlug = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedTipeSlug(): void
    {
        $this->resetPage();
    }

    public function download(int $publicationId, PublicationRepositoryInterface $publicationRepo): StreamedResponse
    {
        return $publicationRepo->download($publicationId);
    }

    public function render(PublicationRepositoryInterface $publicationRepo)
    {
        $tipes = Tipe::withCount(['publications' => function ($query) {
            $query->where('status', PublicationStatus::PUBLISHED);
        }])->get();

        return view('livewire.pages.publication-index', [
            'publications' => $publicationRepo->getPublishedPaginated($this->search, $this->tipeSlug, perPage: 4),
            'tipes' => $tipes,
        ])->layout('components.layouts.public', ['title' => 'Publikasi & Dokumen']);
    }
}
