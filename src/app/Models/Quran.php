<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quran extends Model
{
    /** @var bool */
    public $timestamps = false;

    /** @var string */
    protected $table = 'cnt_quran';

    /** @var array */
    protected $guarded = [];

    /** @var string[] */
    protected $casts = [
        'chapter_id' => 'integer',
        'verse_id' => 'integer',
        'used' => 'boolean',
    ];
}
