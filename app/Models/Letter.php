<?php

namespace App\Models;

use App\Concerns\HasUuid;
use App\Concerns\Loggable;
use App\Concerns\MakeCacheable;
use App\Concerns\UnIncreaseAble;
use Illuminate\Database\Eloquent\Model;

class Letter extends Model
{
    use HasUuid, UnIncreaseAble, Loggable, MakeCacheable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'reference_number',
        'agenda_number',
        'from',
        'to',
        'letter_date',
        'received_date',
        'description',
        'note',
        'type',
        'classification_id',
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
            'reference_number' => 'string',
            'agenda_number' => 'string',
            'from' => 'string',
            'to' => 'string',
            'letter_date' => 'date',
            'received_date' => 'date',
            'description' => 'string',
            'note' => 'string',
            'type' => 'string',
            'classification_id' => 'string',
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
        return 'letter.cache';
    }

    /**
     * Get the letter classification associated with the letter.
     */
    public function classification()
    {
        return $this->belongsTo(LetterClassification::class, 'classification_id');
    }

    /**
     * Get the user associated with the letter.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the attachments associated with the letter.
     */
    public function attachments()
    {
        return $this->hasMany(LetterAttachment::class, 'letter_id');
    }

    /**
     * Get the dispositions associated with the letter.
     */
    public function dispositions()
    {
        return $this->hasMany(LetterDisposition::class, 'letter_id');
    }
}
