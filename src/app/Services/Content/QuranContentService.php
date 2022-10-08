<?php

namespace App\Services\Content;

use App\Models\Quran;

class QuranContentService extends BaseContentService implements ContentServiceInterface
{
    public function __construct()
    {
        $this->class = Quran::class;
        parent::__construct();
    }

    /**
     * getTitle Method.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return sprintf(
            "Chapter %s - %s",
            $this->current->chapter_id,
            $this->current->chapter_name,
        );
    }

    /**
     * getText Method.
     *
     * @return string
     */
    public function getText(): string
    {
        return sprintf(
            "**Verse %s**\n\n%s\n\n*—Marmaduke Pickthall’s English Translation (1930)*",
            $this->current->verse_id,
            $this->current->verse
        );
    }
}
