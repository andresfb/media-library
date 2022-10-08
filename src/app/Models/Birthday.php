<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Birthday extends Model
{
    /** @var bool */
    public $timestamps = false;

    /** @var string */
    protected $table = 'cnt_birthdays';

    /** @var array */
    protected $guarded = [];

    /** @var string[] */
    protected $dates = [
        'birthday',
    ];

    /** @var string[] */
    protected $casts = [
        'used' => "integer",
    ];
}
