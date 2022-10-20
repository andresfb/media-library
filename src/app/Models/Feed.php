<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Builder;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

/**
 * Feed class
 *
 * @property array $tags
 * @property boolean $status
 */
class Feed extends Model
{
    use SoftDeletes;

    /** @var string */
    protected $connection = 'mongodb';

    /** @var string */
    protected $collection = 'feed';

    /** @var string */
    protected $primaryKey = 'id';

    /** @var string[] */
    protected $dates = [
        'date',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    /** @var string[] */
    protected $fillable = [
        'id',
        'name',      'avatar',
        'media',     'poster',
        'mime_type', 'aspect',
        'type',      'slug',
        'title',     'source',
        'content',   'date',
        'status',    'extra_info',
        'comments',  'tags',
    ];


    /**
     * scopePending Method.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 0)
            ->orderBy('date')
            ->limit((int) config("posts.per_page"));
    }
}
