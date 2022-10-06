<?php

namespace App\Services\Content;

use App\Models\Bible;

class BibleContentService extends BaseContentService implements ContentServiceInterface
{
    private string $field = "";

    public function __construct()
    {
        $this->class = Bible::class;
        parent::__construct();
    }

    /** @inheritDoc */
    public function next(): bool
    {
        $next = parent::next();
        if (empty($next)) {
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
}
