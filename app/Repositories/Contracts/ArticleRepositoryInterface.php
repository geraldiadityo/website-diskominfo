<?php

namespace App\Repositories\Contracts;

use App\Models\Article;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ArticleRepositoryInterface
{
    /**
     * @return Collection<int, Article>
     */
    public function getPublished(int $limit = 3): Collection;

    public function getPublishedPaginated(string $search = '', string $categorySlug = '', int $perPage = 9): LengthAwarePaginator;

    /**
     * @return Collection<int, Article>
     */
    public function getRelated(Article $article, int $limit = 4): Collection;

    public function findPublishedBySlug(string $slug): Article;

    public function incrementViews(Article $article): void;
}
