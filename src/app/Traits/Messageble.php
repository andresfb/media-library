<?php

namespace App\Traits;

trait Messageble
{
    private array $messages = [];

    /**
     * getMessages Method.
     *
     * @return string
     */
    public function getMessages(): string
    {
        return collect($this->messages)->implode("\n");
    }

    /**
     * @param string $messages
     */
    public function setMessages(string $messages): void
    {
        $this->messages[] = $messages;
    }

    /**
     * output Method.
     *
     * @return void
     */
    protected function progress(): void
    {
        if (!app()->runningInConsole()) {
            return;
        }

        echo ".";
    }
}
