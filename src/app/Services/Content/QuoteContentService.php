<?php

namespace App\Services\Content;

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
        return sprintf(
            "%s\n\n*—[%s](%s)*",
            $this->current->quote,
            $this->current->author,
            config('posts.search_url') . urlencode($this->current->author)
        );
    }
}
