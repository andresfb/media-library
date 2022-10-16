<?php

namespace App\Models;

use App\Traits\ConvertDateTimeToTimezone;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
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
    protected $casts = [
        'active' => 'boolean',
        'has_exif' => 'boolean',
        'og_item_id' => 'integer',
        'exif' => 'json',
    ];

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
     * registerMediaCollections Method.
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('video')
            ->singleFile()
            ->useDisk('media');

        $this->addMediaCollection('thumb')
            ->acceptsMimeTypes([
                'image/jpeg',
            ])->singleFile()
            ->useDisk('media');

        $this->addMediaCollection('image')
            ->singleFile()
            ->useDisk('media');
    }

    /**
     * scopeUnused Method.
     *
     * @param Builder $query
     * @param int $limit
     * @return Builder
     */
    public function scopeUnused(Builder $query, int $limit): Builder
    {
        return $query->join('posts', 'items.id', '=', 'posts.item_id', 'left outer')
            ->whereNull('posts.id')
            ->inRandomOrder()
            ->limit($limit);
    }

    /**
     * disable Method.
     *
     * @param int $itemId
     * @return void
     */
    public static function disable(int $itemId): void
    {
        if (empty($itemId)) {
            return;
        }

        try {
            $item = Item::find($itemId);
            if (empty($item)) {
                return;
            }

            $item->active = false;
            $item->save();
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
