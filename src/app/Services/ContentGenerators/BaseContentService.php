<?php

namespace App\Services\ContentGenerators;

use Illuminate\Database\Eloquent\Model;

class BaseContentService
{
    protected array $records;

    protected string $tag = '';

    protected string $class = '';

    protected int $totalRecords = 0;

    protected int $maxContentReuse;

    protected Model $model;

    protected ?Model $current = null;

    public function __construct()
    {
        $this->model = new $this->class();
        $pieces = explode('\\', $this->class);
        $this->tag = array_pop($pieces);
        $this->maxContentReuse = (int) config('posts.max_content_reuse');
    }

    /**
     * loadRecords Method.
     *
     * @param int $total
     * @return void
     */
    public function loadRecords(int $total): void
    {
        $maxReuse = 1;
        $records = [];

        while ($maxReuse <= $this->maxContentReuse) {
            $records = $this->loadData($maxReuse, $total);
            if (!empty($records)) {
                break;
            }

            $maxReuse++;
        }

        $this->records = $records;
    }

    /**
     * getTotal Method.
     *
     * @return int
     */
    public function getTotal(): int
    {
        if (!empty($this->totalRecords)) {
            return $this->totalRecords;
        }

        $this->totalRecords = $this->model
            ->where('used', '<=', $this->maxContentReuse)
            ->cache(now()->addDays(7))
            ->count();

        return $this->totalRecords;
    }

    /**
     * next Method.
     *
     * @return bool
     */
    public function next(): bool
    {
        $this->current = array_shift($this->records);
        if (empty($this->current)) {
            return false;
        }

        return true;
    }

    /**
     * getTag Method.
     *
     * @return string
     */
    public function getTag(): string
    {
        return strtolower($this->tag);
    }

    /**
     * markUsed Method.
     *
     * @return void
     */
    public function markUsed(): void
    {
        $this->current->increment('used');
    }

    /**
     * loadData Method.
     *
     * @param int $maxReuse
     * @param int $total
     * @return array
     */
    private function loadData(int $maxReuse, int $total): array
    {
        return $this->model->where('used', '<', $maxReuse)
            ->inRandomOrder()
            ->limit($total)
            ->get()
            ->all();
    }
}
