<?php

namespace App\Livewire\Pages;

use App\Enums\ArticleStatus;
use App\Models\Category;
use App\Repositories\Contracts\ArticleRepositoryInterface;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class NewsIndex extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'kategori')]
    public string $categorySlug = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCategorySlug(): void
    {
        $this->resetPage();
    }

    public function render(ArticleRepositoryInterface $articleRepo)
    {
        $categories = Category::withCount(['articles' => function ($query) {
            $query->where('status', ArticleStatus::PUBLISH)
                ->whereNotNull('publish_at')
                ->where('publish_at', '<=', now());
        }])->get();

        return view('livewire.pages.news-index', [
            'articles' => $articleRepo->getPublishedPaginated($this->search, $this->categorySlug),
            'categories' => $categories,
        ])->layout('components.layouts.public', ['title' => 'Berita Terbaru']);
    }
}
