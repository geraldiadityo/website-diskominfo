<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tipe extends Model
{
    //
    protected $fillable = [
        'name',
        'slug',
    ];

    public function publications(): HasMany
    {
        return $this->hasMany(Publication::class);
    }
}
