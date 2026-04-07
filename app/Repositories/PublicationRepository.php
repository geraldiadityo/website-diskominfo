<?php

namespace App\Repositories;

use App\Enums\PublicationStatus;
use App\Models\Publication;
use App\Repositories\Contracts\PublicationRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PublicationRepository implements PublicationRepositoryInterface
{
    public function getLatestPublished(int $limit = 4): Collection
    {
        return Publication::query()
            ->where('status', PublicationStatus::PUBLISHED)
            ->with('tipe')
            ->latest('published_at')
            ->limit($limit)
            ->get();
    }

    public function getPublishedPaginated(string $search = '', string $tipeSlug = '', int $perPage = 12): LengthAwarePaginator
    {
        return Publication::query()
            ->where('status', PublicationStatus::PUBLISHED)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%'.$search.'%')
                        ->orWhere('description', 'like', '%'.$search.'%');
                });
            })
            ->when($tipeSlug, function ($query) use ($tipeSlug) {
                $query->whereHas('tipe', function ($q) use ($tipeSlug) {
                    $q->where('slug', $tipeSlug);
                });
            })
            ->with('tipe')
            ->latest('published_at')
            ->paginate($perPage);
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
