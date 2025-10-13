<?php

namespace App\Models;

use App\Concerns\HasUuid;
use App\Concerns\Loggable;
use App\Concerns\MakeCacheable;
use App\Concerns\UnIncreaseAble;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasUuid, UnIncreaseAble, Loggable, MakeCacheable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'image',
        'author_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'string',
            'title' => 'string',
            'slug' => 'string',
            'excerpt' => 'string',
            'author_id' => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Set the cache prefix.
     *
     * @return string
     */
    public function setCachePrefix(): string {
        return 'article.cache';
    }

    /**
     * Get the categories that owns the article.
     */
    public function categories()
    {
        return $this->belongsToMany(ArticleCategory::class, ArticleHasCategory::class, 'article_id', 'category_id');
    }

    /**
     * Get the author of the article.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }
}
