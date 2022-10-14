<?php

namespace App\Services\ContentGenerators;

use App\Models\Joke;
use Illuminate\Support\Str;

class JokeContentService extends BaseContentService implements ContentServiceInterface
{
    public function __construct()
    {
        $this->class = Joke::class;
        parent::__construct();
    }

    /**
     * getTitle Method.
     *
     * @return string
     */
    public function getTitle(): string
    {
        $title = $this->cleanString($this->current->title);
        if (strlen($title) > 40) {
            return Str::of($title)->words(6);
        }

        return $title;
    }

    /**
     * getText Method.
     *
     * @return string
     */
    public function getText(): string
    {
        $jokeBase = $this->cleanString($this->current->body);
        $title = $this->cleanString($this->current->title);
        $joke = strlen($title) > 40 && !str_contains($jokeBase, $title)
            ? $title . "\n\n" . $this->current->body
            : $this->current->body;

        $category = sprintf(
            "**Category:** *%s*",
            $this->current->category,
        );

        return $joke . "\n\n" . $category;
    }

    /**
     * cleanString Method.
     *
     * @param string $value
     * @return string
     */
    private function cleanString(string $value): string
    {
        return Str::of($value)
            ->replace(['!', '?', '"', "'"], '')
            ->trim()
            ->toString();
    }
}
