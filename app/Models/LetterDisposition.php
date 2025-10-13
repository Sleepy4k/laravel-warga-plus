<?php

namespace App\Models;

use App\Concerns\HasUuid;
use App\Concerns\Loggable;
use App\Concerns\MakeCacheable;
use App\Concerns\UnIncreaseAble;
use Illuminate\Database\Eloquent\Model;

class LetterDisposition extends Model
{
    use HasUuid, UnIncreaseAble, Loggable, MakeCacheable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'to',
        'due_date',
        'content',
        'note',
        'letter_status_id',
        'letter_id',
        'user_id'
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
            'to' => 'string',
            'due_date' => 'datetime',
            'content' => 'string',
            'note' => 'string',
            'letter_status_id' => 'string',
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
        return 'document.disposition.cache';
    }

    /**
     * Get the letter associated with the disposition.
     */
    public function letter()
    {
        return $this->belongsTo(Letter::class, 'letter_id');
    }

    /**
     * Get the status associated with the disposition.
     */
    public function status()
    {
        return $this->belongsTo(LetterStatus::class, 'letter_status_id');
    }
}
