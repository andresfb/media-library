<?php

namespace App\Services\Content;

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
            ? sprintf("%s\n\n%s", $title, $this->current->body)
            : $this->current->body;

        return sprintf(
            "**Category:** *%s*\n\n%s",
            $this->current->category,
            $joke,
        );
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
