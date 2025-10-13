<?php

namespace App\Models;

use App\Concerns\HasUuid;
use App\Concerns\Loggable;
use App\Concerns\MakeCacheable;
use App\Concerns\UnIncreaseAble;
use Illuminate\Database\Eloquent\Model;

class LetterAttachment extends Model
{
    use HasUuid, UnIncreaseAble, Loggable, MakeCacheable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'path',
        'file_name',
        'file_size',
        'extension',
        'letter_id',
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
            'file_name' => 'string',
            'file_size' => 'string',
            'extension' => 'string',
            'letter_id' => 'string',
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
        return 'document.attachment.cache';
    }

    /**
     * Get the letter that owns the attachment.
     */
    public function letter()
    {
        return $this->belongsTo(Letter::class, 'letter_id');
    }

    /**
     * Get the user that owns the attachment.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
