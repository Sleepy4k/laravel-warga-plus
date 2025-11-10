<?php

namespace App\Models;

use App\Casts\AsHash;
use App\Concerns\HasUuid;
use App\Concerns\Loggable;
use App\Concerns\MakeCacheable;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasUuid, Loggable, MakeCacheable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'content',
        'location',
        'status',
        'category_id',
        'user_id',
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
            'title' => AsHash::class,
            'content' => AsHash::class,
            'location' => AsHash::class,
            'status' => 'string',
            'category_id' => 'string',
            'user_id' => 'string',
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
        return 'report.cache';
    }

    /**
     * Set paginate from collection.
     */
    public static function setPaginateFromCollection($collection, $perPage)
    {
        $page = request()->get('page', 1);
        $total = $collection->count();
        $items = $collection->slice(($page - 1) * $perPage, $perPage)->values();

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    /**
     * Get the category that owns the report.
     */
    public function category()
    {
        return $this->belongsTo(ReportCategory::class, 'category_id', 'id');
    }

    /**
     * Get the user that owns the report.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the attachments for the report.
     */
    public function attachments()
    {
        return $this->hasMany(ReportAttachment::class, 'report_id');
    }

    /**
     * Get the progress updates for the report.
     */
    public function progress()
    {
        return $this->hasMany(ReportProgress::class, 'report_id');
    }
}
