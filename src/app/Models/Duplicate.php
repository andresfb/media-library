<?php

namespace App\Models;

use App\Traits\ConvertDateTimeToTimezone;
use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Models\Duplicate
 *
 * @property int $id
 * @property int $item_id
 * @property string $hash
 * @property string $name_hash
 * @property string $og_path
 * @property string $og_file
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static \Database\Factories\DuplicateFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Duplicate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Duplicate newQuery()
 * @method static \Illuminate\Database\Query\Builder|Duplicate onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Duplicate query()
 * @method static \Illuminate\Database\Eloquent\Builder|Duplicate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Duplicate whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Duplicate whereHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Duplicate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Duplicate whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Duplicate whereNameHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Duplicate whereOgFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Duplicate whereOgPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Duplicate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Duplicate withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Duplicate withoutTrashed()
 * @mixin Eloquent
 */
class Duplicate extends Model
{
    use HasFactory, SoftDeletes, ConvertDateTimeToTimezone;

    /** @var array */
    protected $guarded = [];

    /** @var string[] */
    protected $dates = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];
}
