<?php

namespace App\Models;

use App\Concerns\HasUuid;
use App\Concerns\Loggable;
use App\Concerns\MakeCacheable;
use Illuminate\Database\Eloquent\Model;

class ReportAttachment extends Model
{
    use HasUuid, Loggable, MakeCacheable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'path',
        'report_id',
        'user_id',
        'file_name',
        'file_size',
        'extension',
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
            'user_id' => 'string',
            'file_name' => 'string',
            'file_size' => 'string',
            'extension' => 'string',
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
        return 'report.attachment.cache';
    }

    /**
     * Get the report that owns the attachment.
     */
    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id');
    }

    /**
     * Get the user that owns the attachment.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
