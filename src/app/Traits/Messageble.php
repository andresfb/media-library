<?php

namespace App\Traits;

trait Messageble
{
    private string $messages = "";

    /**
     * getMessages Method.
     *
     * @return string
     */
    public function getMessages(): string
    {
        return $this->messages;
    }

    /**
     * @param string $messages
     */
    public function setMessages(string $messages): void
    {
        $this->messages = $messages;
    }
}
