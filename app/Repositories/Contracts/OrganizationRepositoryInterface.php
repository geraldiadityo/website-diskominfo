<?php

namespace App\Repositories\Contracts;

use App\Models\OrganizationMember;
use Illuminate\Database\Eloquent\Collection;

interface OrganizationRepositoryInterface
{
    /**
     * @return Collection<int, OrganizationMember>
     */
    public function getHierarchy(): Collection;

    public function getLeader(): ?OrganizationMember;
}
