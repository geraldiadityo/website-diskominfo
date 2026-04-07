<?php

namespace App\Repositories;

use App\Models\OrganizationMember;
use App\Repositories\Contracts\OrganizationRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class OrganizationRepository implements OrganizationRepositoryInterface
{
    public function getHierarchy(): Collection
    {
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
    }

    public function getLeader(): ?OrganizationMember
    {
        return OrganizationMember::query()
            ->where('is_active', true)
            ->with(['position', 'departement'])
            ->whereNull('parent_id')
            ->first();
    }
}
