<?php

namespace App\Services\Content;

interface ContentServiceInterface
{
    public function next(): bool;

    public function getTitle(): string;

    public function getText(): string;

    public function getTag(): string;

    public function loadRecords(int $total): void;

    public function getTotal(): int;

    public function markUsed(): void;
}
