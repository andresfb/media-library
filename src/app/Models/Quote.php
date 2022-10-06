<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    public $timestamps = false;

    /** @var array */
    protected $guarded = [];

    /** @var string[] */
    protected $casts = [
        'used' => 'integer'
    ];
}
