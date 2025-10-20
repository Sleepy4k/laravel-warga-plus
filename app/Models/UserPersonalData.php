<?php

namespace App\Models;

use App\Casts\AsHash;
use App\Concerns\HasUuid;
use App\Concerns\Loggable;
use App\Concerns\MakeCacheable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPersonalData extends Model
{
    use HasFactory, HasUuid, Loggable, MakeCacheable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'gender',
        'birth_date',
        'job',
        'address',
        'avatar',
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
            'user_id' => 'string',
            'first_name' => 'string',
            'last_name' => 'string',
            'gender' => 'string',
            'birth_date' => 'date:Y-m-d',
            'job' => AsHash::class,
            'address' => AsHash::class,
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
        return 'user.personal.data.cache';
    }

    /**
     * Get the user that owns the personal data.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the full name of the user.
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    /**
     * Get user avatar data from the personal data.
     */
    public function userAvatar()
    {
        return $this->avatar ?: asset('img/avatars/silhouette.jpg'); // Fallback to a default avatar if none is set
    }
}
