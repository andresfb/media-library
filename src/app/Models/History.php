<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    /** @var bool */
    public $timestamps = false;

    /** @var string */
    protected $table = 'cnt_history';

    /** @var array */
    protected $guarded = [];

    /** @var string[] */
    protected $dates = [
        'event_date',
    ];

    /** @var string[] */
    protected $casts = [
        'used' => "integer",
    ];
}
