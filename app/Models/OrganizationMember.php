<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class OrganizationMember extends Model
{
    //
    protected $fillable = [
        'name',
        'position_id',
        'departement_id',
        'parent_id',
        'bio',
        'photo',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function departement(): BelongsTo
    {
        return $this->belongsTo(Departement::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(OrganizationMember::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(OrganizationMember::class, 'parent_id')
            ->orderBy('sort_order');
    }

    protected static function booted()
    {
        static::deleting(function (OrganizationMember $data) {
            if ($data->photo && Storage::disk('public')->exists($data->photo)) {
                Storage::disk('public')->delete($data->photo);
            }
        });

        static::updating(function (OrganizationMember $data) {
            if ($data->isDirty('photo')) {
                $oldImage = $data->getOriginal('photo');
                if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                    Storage::disk('public')->delete($oldImage);
                }
            }
        });
    }
}
