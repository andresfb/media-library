<?php

namespace App\Models;

use App\Traits\ConvertDateTimeToTimezone;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * App\Models\Item
 */
class Item extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    use SoftDeletes, ConvertDateTimeToTimezone;

    /** @var array */
    protected $guarded = [];

    /** @var string[] */
    protected $dates = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];


    /**
     * exists Method.
     *
     * @param string $hash
     * @param string $path
     * @return bool
     */
    public static function found(string $hash, string $path): bool
    {
        $item = Item::whereHash($hash)
            ->whereOgPath($path)
            ->first();

        return !empty($item);
    }

    /**
     * events Method.
     *
     * @return HasMany
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    /**
     * posts Method.
     *
     * @return HasMany
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * registerMediaCollections Method.
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('video')
            ->singleFile()
            ->useDisk('media');

        $this->addMediaCollection('image')
            ->singleFile()
            ->useDisk('media');
    }

    /**
     * scopeUnused Method.
     *
     * @param Builder $query
     * @param string $type
     * @param int $limit
     * @return Builder
     */
    public function scopeUnused(Builder $query, string $type, int $limit): Builder
    {
        return $query->where('items.type', $type)
            ->join('posts', 'items.id', '=', 'posts.item_id', 'left outer')
            ->whereNull('posts.id')
            ->inRandomOrder()
            ->limit($limit);
    }
}
