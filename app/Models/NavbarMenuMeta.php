<?php

namespace App\Models;

use App\Concerns\HasUuid;
use App\Concerns\Loggable;
use App\Concerns\MakeCacheable;
use App\Concerns\UnIncreaseAble;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NavbarMenuMeta extends Model
{
    use HasFactory, HasUuid, UnIncreaseAble, Loggable, MakeCacheable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'navbar_menu_meta';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'route',
        'permissions',
        'parameters',
        'active_routes',
        'is_sortable',
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
            'route' => 'string',
            'permissions' => 'array',
            'parameters' => 'array',
            'active_routes' => 'string',
            'is_sortable' => 'boolean',
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
        return 'navbar.menu.meta.cache';
    }

    /**
     * Get the menu items associated with the meta.
     */
    public function menus()
    {
        return $this->hasMany(NavbarMenu::class, 'meta_id', 'id');
    }
}
