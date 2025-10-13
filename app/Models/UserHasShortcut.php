<?php

namespace App\Models;

use App\Concerns\Loggable;
use App\Concerns\MakeCacheable;
use Illuminate\Database\Eloquent\Model;

class UserHasShortcut extends Model
{
    use Loggable, MakeCacheable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'shortcut_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'user_id' => 'string',
            'shortcut_id' => 'string',
        ];
    }

    /**
     * Set the cache prefix.
     *
     * @return string
     */
    public function setCachePrefix(): string {
        return 'user.has.shortcuts.cache';
    }

    /**
     * Get the user that owns the shortcut.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the shortcut that belongs to the user.
     */
    public function shortcut()
    {
        return $this->belongsTo(Shortcut::class, 'shortcut_id', 'id');
    }
}
