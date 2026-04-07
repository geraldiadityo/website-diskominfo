<?php

namespace App\Repositories;

use App\Enums\ArticleStatus;
use App\Models\Article;
use App\Repositories\Contracts\ArticleRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ArticleRepository implements ArticleRepositoryInterface
{
    public function getPublished(int $limit = 3): Collection
    {
        return Article::query()
            ->where('status', ArticleStatus::PUBLISH)
            ->whereNotNull('publish_at')
            ->where('publish_at', '<=', now())
            ->with(['author', 'category'])
            ->latest('publish_at')
            ->limit($limit)
            ->get();
    }

    public function getPublishedPaginated(string $search = '', string $categorySlug = '', int $perPage = 9): LengthAwarePaginator
    {
        return Article::query()
            ->where('status', ArticleStatus::PUBLISH)
            ->whereNotNull('publish_at')
            ->where('publish_at', '<=', now())
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%'.$search.'%')
                        ->orWhere('content', 'like', '%'.$search.'%');
                });
            })
            ->when($categorySlug, function ($query) use ($categorySlug) {
                $query->whereHas('category', function ($q) use ($categorySlug) {
                    $q->where('slug', $categorySlug);
                });
            })
            ->with(['author', 'category'])
            ->latest('publish_at')
            ->paginate($perPage);
    }

    public function getRelated(Article $article, int $limit = 4): Collection
    {
        return Article::query()
            ->where('status', ArticleStatus::PUBLISH)
            ->where('id', '!=', $article->id)
            ->where('category_id', $article->category_id)
            ->whereNotNull('publish_at')
            ->where('publish_at', '<=', now())
            ->with(['author', 'category'])
            ->latest('publish_at')
            ->limit($limit)
            ->get();
    }

    public function findPublishedBySlug(string $slug): Article
    {
        $article = Article::query()
            ->where('slug', $slug)
            ->with(['author', 'category', 'tags'])
            ->firstOrFail();

        abort_unless($article->status === ArticleStatus::PUBLISH, 404);

        return $article;
    }

    public function incrementViews(Article $article): void
    {
        $article->incrementQuietly('views');
    }
}
