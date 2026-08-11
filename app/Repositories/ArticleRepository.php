<?php

namespace App\Repositories;

use App\Enums\ArticleStatus;
use App\Models\Article;
use App\Repositories\Contracts\ArticleRepositoryInterface;
use App\Traits\CacheableRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ArticleRepository implements ArticleRepositoryInterface
{
    use CacheableRepository;
    protected string $cacheTag = 'articles';
    protected int $cacheTtl = 3600;

    public function getPublished(int $limit = 3): Collection
    {
        $cacheKey = "articles_latest_{$limit}";
        return $this->executeWithCache($cacheKey, function () use ($limit) {
            return Article::query()
                ->where('status', ArticleStatus::PUBLISH)
                ->whereNotNull('publish_at')
                ->where('publish_at', '<=', now())
                ->with(['author', 'category'])
                ->latest('publish_at')
                ->limit($limit)
                ->get();
        });
    }

    public function getPublishedPaginated(string $search = '', string $categorySlug = '', int $perPage = 9): LengthAwarePaginator
    {
        $page = request()->get('page', 1);
        $cacheKey = "articles_pub_page_{$page}_limit_{$perPage}";
        if ($search) {
            $cacheKey .= "_search_" . md5($search);
        }

        if ($categorySlug) {
            $cacheKey .= "_cat_" . $categorySlug;
        }

        return $this->executeWithCache($cacheKey, function () use ($search, $categorySlug, $perPage) {
            return Article::query()
                ->where('status', ArticleStatus::PUBLISH)
                ->whereNotNull('publish_at')
                ->where('publish_at', '<=', now())
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('title', 'like', '%' . $search . '%')
                            ->orWhere('content', 'like', '%' . $search . '%');
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
        });
    }

    public function getRelated(Article $article, int $limit = 4): Collection
    {
        $cacheKey = "article_related_{$article->id}_limit_{$limit}";
        return $this->executeWithCache($cacheKey, function () use ($article, $limit) {
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
        });
    }

    public function findPublishedBySlug(string $slug): Article
    {
        $cacheKey = "article_detail_slug_{$slug}";

        return $this->executeWithCache($cacheKey, function () use ($slug) {
            $article = Article::query()
                ->where('slug', $slug)
                ->with(['author', 'category', 'tags'])
                ->firstOrFail();

            abort_unless($article->status === ArticleStatus::PUBLISH, 404);

            return $article;
        });
    }

    public function incrementViews(Article $article): void
    {
        $article->incrementQuietly('views');
    }
}
