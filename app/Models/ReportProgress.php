<?php

namespace App\Models;

use App\Casts\AsHash;
use App\Concerns\HasUuid;
use App\Concerns\Loggable;
use App\Concerns\MakeCacheable;
use Illuminate\Database\Eloquent\Model;

class ReportProgress extends Model
{
    use HasUuid, Loggable, MakeCacheable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'report_id',
        'title',
        'description',
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
            'report_id' => 'string',
            'title' => AsHash::class,
            'description' => AsHash::class,
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
        return 'report.progress.cache';
    }

    /**
     * Get the report that owns the attachment.
     */
    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id');
    }
}
