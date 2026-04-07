<?php

namespace App\Repositories\Contracts;

use App\Models\Publication;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

interface PublicationRepositoryInterface
{
    /**
     * @return Collection<int, Publication>
     */
    public function getLatestPublished(int $limit = 4): Collection;

    public function getPublishedPaginated(string $search = '', string $tipeSlug = '', int $perPage = 12): LengthAwarePaginator;

    public function download(int $publicationId): StreamedResponse;
}
