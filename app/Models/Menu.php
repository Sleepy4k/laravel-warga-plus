<?php

namespace App\Models;

use App\Concerns\HasUuid;
use App\Concerns\Loggable;
use App\Concerns\MakeCacheable;
use App\Concerns\UnIncreaseAble;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory, HasUuid, UnIncreaseAble, Loggable, MakeCacheable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'order',
        'is_spacer',
        'parent_id',
        'meta_id',
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
            'name' => 'string',
            'order' => 'integer',
            'parent_id' => 'string',
            'meta_id' => 'string',
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
        return 'menu.cache';
    }

    /**
     * Get the meta associated with the menu.
     */
    public function meta()
    {
        return $this->belongsTo(MenuMeta::class, 'meta_id', 'id');
    }

    /**
     * Get the parent menu.
     */
    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id', 'id');
    }

    /**
     * Get the child menus.
     */
    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id', 'id');
    }

    /**
     * Get the full path of the menu.
     *
     * @return string
     */
    public function getFullPathAttribute(): string
    {
        $path = $this->name;

        if ($this->parent) {
            $path = $this->parent->getFullPathAttribute() . ' > ' . $path;
        }

        return $path;
    }

    /**
     * Get the menu's meta data.
     *
     * @return array
     */
    public function getMetaData(): array
    {
        return $this->meta ? $this->meta->toArray() : [];
    }
}
