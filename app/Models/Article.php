<?php

namespace App\Models;

use App\Enums\ArticleStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Article extends Model
{
    //
    protected $fillable = [
        'title',
        'slug',
        'content',
        'featured_image',
        'author_id',
        'category_id',
        'status',
        'seo_title',
        'seo_description',
        'published_at',
        'views'
    ];

    protected $guarded = [];

    protected $casts = [
        'status' => ArticleStatus::class,
        'published_at' => 'datetime',
        'views' => 'integer'
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'article_tags');
    }

    protected static function booted()
    {
        static::deleting(function (Article $article) {
            if ($article->featured_image && Storage::disk('public')->exists($article->featured_image)) {
                Storage::disk('public')->delete($article->featured_image);
            }
        });

        static::updating(function (Article $article) {
            if ($article->isDirty('featured_image')) {
                $oldImage = $article->getOriginal('featured_image');
                if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                    Storage::disk('public')->delete($oldImage);
                }
            }
        });
    }
}
