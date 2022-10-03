<?php

namespace App\Services\Content;

interface ContentServiceInterface
{
    public function next(): bool;

    public function getTitle(): string;

    public function getText(): string;

    public function getTag(): string;

    public function setTotal(int $total): void;
}
