<?php

namespace App\Services\Content;

use App\Models\Bible;

class BibleContentService implements ContentServiceInterface
{
    private array $records;

    private Bible $current;

    private string $field = "";


    /**
     * loadRecords Method.
     *
     * @param int $total
     * @return void
     */
    public function loadRecords(int $total): void
    {
        $this->records = Bible::where('used', '<=', 10)
            ->inRandomOrder()
            ->limit($total)
            ->get()
            ->all();
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

        do {
            $this->field = $this->current->getRandomField();
        } while (empty($this->current->{$this->field}));

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
            $this->current->getTableInfo()
                ->where('key', $this->field)
                ->first()['value'],
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
     * markUsed Method.
     *
     * @return void
     */
    public function markUsed(): void
    {
        $this->current->increment('used');
    }
}
