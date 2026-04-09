<?php

namespace App\Livewire\Pages;

use App\Models\Article;
use App\Models\Tag;
use App\Repositories\Contracts\ArticleRepositoryInterface;
use Illuminate\Support\Str;
use Livewire\Component;

class NewsDetail extends Component
{
    public Article $article;

    public function mount(Article $article, ArticleRepositoryInterface $articleRepo): void
    {
        $this->article = $articleRepo->findPublishedBySlug($article->slug);
        $articleRepo->incrementViews($this->article);
    }

    public function render(ArticleRepositoryInterface $articleRepo)
    {
        $popularTags = Tag::withCount('articles')
            ->orderByDesc('articles_count')
            ->limit(10)
            ->get();

        return view('livewire.pages.news-detail', [
            'relatedArticles' => $articleRepo->getRelated($this->article),
            'popularTags' => $popularTags,
        ])->layout('components.layouts.public', [
            'title' => $this->article->seo_title ?? $this->article->title,
            'description' => $this->article->seo_description ?? Str::limit(strip_tags($this->article->content), 160),
            'ogImage' => $this->article->featured_image ? asset('storage/' . $this->article->featured_image) : null,
        ]);
    }
}
