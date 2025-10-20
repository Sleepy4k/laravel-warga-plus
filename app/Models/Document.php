<?php

namespace App\Models;

use App\Concerns\HasUuid;
use App\Concerns\Loggable;
use App\Concerns\MakeCacheable;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasUuid, Loggable, MakeCacheable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'category_id',
        'description',
        'is_archived',
        'last_modified_at',
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
            'category_id' => 'string',
            'description' => 'string',
            'is_archived' => 'boolean',
            'last_modified_at' => 'datetime',
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
        return 'document.cache';
    }

    /**
     * Get the category that owns the document.
     */
    public function category()
    {
        return $this->belongsTo(DocumentCategory::class, 'category_id');
    }

    /**
     * Get the versions associated with the document.
     */
    public function versions()
    {
        return $this->hasMany(DocumentVersion::class, 'document_id');
    }

    /**
     * Get the user who last modified the document.
     */
    public function lastModifiedBy()
    {
        return $this->belongsTo(User::class, 'last_modified_by');
    }

    /**
     * Get the user who created the document.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
