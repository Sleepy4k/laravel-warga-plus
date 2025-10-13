<?php

namespace App\Models;

use App\Concerns\HasUuid;
use App\Concerns\Loggable;
use App\Concerns\MakeCacheable;
use App\Concerns\UnIncreaseAble;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NavbarMenu extends Model
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
        return 'navbar.menu.cache';
    }

    /**
     * Get the meta associated with the menu.
     */
    public function meta()
    {
        return $this->belongsTo(NavbarMenuMeta::class, 'meta_id', 'id');
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
