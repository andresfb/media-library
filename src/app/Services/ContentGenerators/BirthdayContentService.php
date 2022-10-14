<?php

namespace App\Services\ContentGenerators;

use App\Models\Birthday;

class BirthdayContentService extends BaseContentService implements ContentServiceInterface
{
    public function __construct()
    {
        $this->class = Birthday::class;
        parent::__construct();
    }

    /**
     * getTitle Method.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->current->birthday->toFormattedDateString() . " is the birthday of";
    }

    /**
     * getText Method.
     *
     * @return string
     */
    public function getText(): string
    {
        $name = sprintf(
            '[%s](%s)',
            $this->current->name,
            config('posts.search_url') . urlencode(strtolower($this->current->name)),
        );

        if (!empty($this->current->description)) {
            $name .= "\n\n" . ucfirst($this->current->description);
        }

        return $name;
    }
}
