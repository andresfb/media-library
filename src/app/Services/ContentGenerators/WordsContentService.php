<?php

namespace App\Services\ContentGenerators;

use App\Models\Word;

class WordsContentService extends BaseContentService implements ContentServiceInterface
{
    public function __construct()
    {
        $this->class = Word::class;
        parent::__construct();
    }

    /**
     * getTitle Method.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return "Word Definition";
    }

    /**
     * getText Method.
     *
     * @return string
     */
    public function getText(): string
    {
        $word = sprintf(
            '**[%s](%s)**',
            ucwords(str_replace("_", " ", $this->current->word)),
            config('posts.search_url') . urlencode(strtolower($this->current->word)),
        );

        return $word . "\n\n" . ucfirst($this->current->definition);
    }
}
