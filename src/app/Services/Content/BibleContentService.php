<?php

namespace App\Services\Content;

use App\Models\Bible;
use Illuminate\Database\Eloquent\Collection;

class BibleContentService implements ContentServiceInterface
{
    private Collection $records;

    private Bible $current;

    private string $field = "";


    /**
     * next Method.
     *
     * @return bool
     */
    public function next(): bool
    {
        $this->current = $this->records->shift()->first();
        if (empty($this->current)) {
            return false;
        }

        do {
            $this->field = $this->current->getRandomField();
        } while (!empty($this->current->{$this->field}));

        return true;
    }

    /**
     * getTitle Method.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->current->verse;
    }

    /**
     * getText Method.
     *
     * @return string
     */
    public function getText(): string
    {
        return sprintf(
            "%s\n\n*—%s*",
            $this->current->{$this->field},
            $this->current->getTableInfo()[$this->field],
        );
    }

    /**
     * getTag Method.
     *
     * @return string
     */
    public function getTag(): string
    {
        return "Bible";
    }

    /**
     * setTotal Method.
     *
     * @param int $total
     * @return void
     */
    public function setTotal(int $total): void
    {
        $this->records = Bible::whereUsed(false)
            ->inRandomOrder()
            ->limit($total)
            ->get();
    }
}
