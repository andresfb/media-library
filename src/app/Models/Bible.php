<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Bible extends Model
{
    private Collection $tableInfo;

    /** @var array */
    protected $guarded = [];

    /** @var string[] */
    protected $casts = [
        'used' => 'int'
    ];

    /** @var bool */
    public $timestamps = false;

    /**
     * getRandomField Method.
     *
     * @return string
     */
    public function getRandomField(): string
    {
        $info = $this->getTableInfo();
        if (empty($info)) {
            return "";
        }

        return $info->random(1)->first()['key'];
    }

    /**
     * getTableInfo Method.
     *
     * @return Collection
     */
    public function getTableInfo(): Collection
    {
        if (!empty($this->tableInfo)) {
            return $this->tableInfo;
        }

        $key = md5(self::class);
        $info = Cache::get($key, []);
        if (!empty($info)) {
            $this->tableInfo = $info;
            return $info;
        }

        $struct = DB::select("SHOW FULL COLUMNS FROM {$this->getTable()}");
        if (empty($struct)) {
            return collect([]);
        }

        $info = [];
        foreach ($struct as $item) {
            if (empty($item->Comment)) {
                continue;
            }

            $info[] = ['key' => $item->Field, 'value' => $item->Comment];
        }

        $this->tableInfo = collect($info);
        Cache::put($key, $this->tableInfo, now()->addWeek());
        return $this->tableInfo;
    }
}
