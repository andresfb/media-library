<?php

namespace App\Services\ContentGenerators;

use App\Models\History;

class HistoryContentService extends BaseContentService implements ContentServiceInterface
{
    public function __construct()
    {
        $this->class = History::class;
        parent::__construct();
    }

    /**
     * getTitle Method.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return "On " . $this->current->event_date->toFormattedDateString();
    }

    /**
     * getText Method.
     *
     * @return string
     */
    public function getText(): string
    {
        return $this->current->event;
    }
}
