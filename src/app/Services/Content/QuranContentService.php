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
        $verseId = sprintf(
            "**Verse %s**",
            $this->current->verse_id,
        );

        return $verseId
            . "\n\n"
            . $this->current->verse
            . "\n\n"
            . "*—Marmaduke Pickthall’s English Translation (1930)*";
    }
}
