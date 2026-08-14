<?php

namespace App\Repositories;

use App\Enums\PublicationStatus;
use App\Models\Publication;
use App\Repositories\Contracts\PublicationRepositoryInterface;
use App\Traits\CacheableRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PublicationRepository implements PublicationRepositoryInterface
{
    use CacheableRepository;

    protected string $cacheTag = 'publications';
    protected int $cacheTtl = 3600;

    private function buildPublishedQuery(string $search = '', string $tipeSlug = '')
    {
        return Publication::query()
            ->where('status', PublicationStatus::PUBLISHED)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%');
                });
            })
            ->when($tipeSlug, function ($query) use ($tipeSlug) {
                $query->whereHas('tipe', function ($q) use ($tipeSlug) {
                    $q->where('slug', $tipeSlug);
                });
            })
            ->with('tipe')
            ->latest('published_at');
    }

    public function getLatestPublished(int $limit = 4): Collection
    {
        return Publication::query()
            ->where('status', PublicationStatus::PUBLISHED)
            ->with('tipe')
            ->latest('published_at')
            ->limit($limit)
            ->get();
    }

    public function getPublishedPaginated(string $search = '', string $tipeSlug = '', int $perPage = 12, int $page = 1): LengthAwarePaginator
    {
        if (!empty($search)) {
            return $this->buildPublishedQuery($search, $tipeSlug)->paginate($perPage, ['*'], 'page', $page);
        }

        $cacheKey = "publications_page_{$page}_limit_{$perPage}";
        if ($tipeSlug) {
            $cacheKey .= "_tipe_" . $tipeSlug;
        }

        return Cache::tags([$this->cacheTag])->remember($cacheKey, 600, function () use ($tipeSlug, $perPage, $page) {
            return $this->buildPublishedQuery('', $tipeSlug)->paginate($perPage, ['*'], 'page', $page);
        });
    }

    public function download(int $publicationId): StreamedResponse
    {
        $publication = Publication::findOrFail($publicationId);
        $publication->increment('download_count');

        $fileName = basename($publication->file_path);

        return response()->streamDownload(function () use ($publication) {
            echo Storage::disk('public')->get($publication->file_path);
        }, $fileName);
    }
}
