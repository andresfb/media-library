<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    /** @var bool */
    public $timestamps = false;

    /** @var string */
    protected $table = 'cnt_words';

    /** @var array */
    protected $guarded = [];

    /** @var string[] */
    protected $casts = [
        'used' => "integer",
    ];
}
