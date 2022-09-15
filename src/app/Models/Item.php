<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\Tags\HasTags;

/**
 * App\Models\Item
 *
 * @property int $id
 * @property string $hash
 * @property string $og_path
 * @property string $og_file
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Event[] $events
 * @property-read int|null $events_count
 * @property-read MediaCollection|Media[] $media
 * @property-read int|null $media_count
 * @property Collection|Tag[] $tags
 * @property-read int|null $tags_count
 * @method static \Database\Factories\ItemFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Item newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Item newQuery()
 * @method static \Illuminate\Database\Query\Builder|Item onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Item query()
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereNameHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereOgFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereOgPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Item withAllTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder|Item withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Item withAnyTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Query\Builder|Item withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Item withoutTrashed()
 * @mixin Eloquent
 */
class Item extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    use HasTags, SoftDeletes;

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

//        $this->addMediaConversion('preview')
//            ->format('jpg')
//            ->fit(Manipulations::FIT_CROP, 600, 600)
//            ->performOnCollections('image');
    }
}
