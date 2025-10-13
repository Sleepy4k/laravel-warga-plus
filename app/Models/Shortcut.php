<?php

namespace App\Models;

use App\Concerns\HasUuid;
use App\Concerns\Loggable;
use App\Concerns\MakeCacheable;
use App\Concerns\UnIncreaseAble;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shortcut extends Model
{
    use HasFactory, HasUuid, UnIncreaseAble, Loggable, MakeCacheable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'label',
        'route',
        'icon',
        'description',
        'permissions',
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
            'label' => 'string',
            'route' => 'string',
            'icon' => 'string',
            'description' => 'string',
            'permissions' => 'array',
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
        return 'shortcut.cache';
    }

    /**
     * Get all data that current user can access.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, static>
     */
    public static function getAllAccessible()
    {
        $permissions = getUserPermissions();

        return static::orderBy('created_at', 'asc')
            ->get(['id', 'label', 'route', 'icon', 'description', 'permissions', 'created_at', 'updated_at'])
            ->filter(function ($shortcut) use ($permissions) {
                return empty($shortcut->permissions) ||
                    (is_array($shortcut->permissions) && count(array_intersect($permissions, $shortcut->permissions)) > 0);
            })
            ->values();
    }

    /**
     * Get the users that belong to the shortcut from user has shortcuts table.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, UserHasShortcut::class, 'shortcut_id', 'user_id');
    }
}
