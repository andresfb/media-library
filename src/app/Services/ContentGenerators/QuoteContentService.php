<?php

namespace App\Services\ContentGenerators;

use App\Models\Quote;

class QuoteContentService extends BaseContentService implements ContentServiceInterface
{
    public function __construct()
    {
        $this->class = Quote::class;
        parent::__construct();
    }

    /**
     * getTitle Method.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return ucfirst($this->current->category);
    }

    /**
     * getText Method.
     *
     * @return string
     */
    public function getText(): string
    {
        $author = sprintf(
            "*—[%s](%s)*",
            $this->current->author,
            config('posts.search_url') . urlencode($this->current->author)
        );

        return $this->current->quote . "\n\n" . $author;
    }
}
