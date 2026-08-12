<?php

namespace App\Repositories;

use App\Models\OrganizationMember;
use App\Repositories\Contracts\OrganizationRepositoryInterface;
use App\Traits\CacheableRepository;
use Illuminate\Database\Eloquent\Collection;

class OrganizationRepository implements OrganizationRepositoryInterface
{
    use CacheableRepository;
    protected string $cacheTag = 'organizations';
    protected int $cacheTtl = 3600;

    public function getHierarchy(): Collection
    {
        return $this->executeWithCache('organization_hierarchy', function () {
            return OrganizationMember::query()
                ->where('is_active', true)
                ->with(['position', 'departement', 'children' => function ($query) {
                    $query->where('is_active', true)
                        ->with(['position', 'departement', 'children' => function ($q) {
                            $q->where('is_active', true)
                                ->with(['position', 'departement'])
                                ->orderBy('sort_order');
                        }])
                        ->orderBy('sort_order');
                }])
                ->whereNull('parent_id')
                ->orderBy('sort_order')
                ->get();
        });
    }

    public function getLeader(): ?OrganizationMember
    {
        return $this->executeWithCache('organization_leader', function () {
            return OrganizationMember::query()
                ->where('is_active', true)
                ->with(['position', 'departement'])
                ->whereNull('parent_id')
                ->first();
        });
    }
}
