<?php

namespace App\Models;

use App\Concerns\Loggable;
use App\Concerns\MakeCacheable;
use Illuminate\Database\Eloquent\Model;

class ArticleHasCategory extends Model
{
    use Loggable, MakeCacheable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'article_id',
        'category_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'article_id' => 'string',
            'category_id' => 'string',
        ];
    }

    /**
     * Set the cache prefix.
     *
     * @return string
     */
    public function setCachePrefix(): string {
        return 'article.has.categories.cache';
    }

    /**
     * Get the article that belongs to the category.
     */
    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id', 'id');
    }

    /**
     * Get the category that belongs to the article.
     */
    public function category()
    {
        return $this->belongsTo(ArticleCategory::class, 'category_id', 'id');
    }
}
