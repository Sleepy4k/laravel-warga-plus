<?php

namespace App\Models;

use App\Concerns\HasUuid;
use App\Concerns\Loggable;
use App\Concerns\MakeCacheable;
use Illuminate\Database\Eloquent\Model;

class DocumentVersion extends Model
{
    use HasUuid, Loggable, MakeCacheable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'file_name',
        'file_path',
        'file_size',
        'file_type',
        'document_id',
        'user_id',
        'uploaded_at',
        'version_number',
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
            'file_name' => 'string',
            'file_size' => 'string',
            'file_type' => 'string',
            'document_id' => 'string',
            'user_id' => 'string',
            'uploaded_at' => 'datetime',
            'version_number' => 'integer',
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
        return 'document.version.cache';
    }

    /**
     * Get the document associated with the version.
     */
    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }

    /**
     * Get the user who uploaded the version.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
