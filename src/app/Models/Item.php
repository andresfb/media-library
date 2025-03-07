<?php

namespace App\Models;

use App\Traits\ConvertDateTimeToTimezone;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * App\Models\Item
 */
class Item extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use CascadeSoftDeletes;
    use SoftDeletes;
    use ConvertDateTimeToTimezone;

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

    /** @var array */
    protected array $cascadeDeletes = ['media'];

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

    public function itemFile(): HasOne
    {
        return $this->hasOne(ItemFile::class);
    }

    public function scopePendingMove(Builder $query): Builder
    {
        return $query->where('active', true)
            ->whereDoesntHave('itemFile')
            ->limit(config('move-to-minio.max_per_run'));
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
            /** @var Item $item */
            $item = Item::find($itemId);
            if (empty($item)) {
                return;
            }

            $item->active = false;
            $item->save();
            Log::info("Disabled Item Id: $itemId");
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
