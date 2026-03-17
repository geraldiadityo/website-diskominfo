<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrganizationMember extends Model
{
    //
    protected $fillable = [
        'name',
        'position',
        'departement',
        'bio',
        'parent_id',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(OrganizationMember::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(OrganizationMember::class, 'parent_id')
            ->orderBy('sort_order');
    }
}
