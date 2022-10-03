<?php

namespace App\Services;

use App\Services\Content\BibleContentService;
use App\Services\Content\ContentServiceInterface;

class ContentOrchestratorService
{
    private int $total;

    private array $sources = [];

    private ContentServiceInterface $service;


    public function __construct()
    {
        $this->total = (int) config('items.max_random_posts');
    }

    /**
     * next Method.
     *
     * @return bool
     */
    public function next(): bool
    {
        if (empty($this->sources)) {
            $this->loadSources();
        }

        if ($this->service->next()) {
            return true;
        }

        $source = array_shift($this->sources);
        if (empty($source)) {
            return false;
        }

        return $this->service->next();
    }

    /**
     * getTitle Method.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->service->getTitle();
    }

    /**
     * getText Method.
     *
     * @return string
     */
    public function getText(): string
    {
        return $this->service->getText();
    }

    /**
     * getTag Method.
     *
     * @return string
     */
    public function getTag(): string
    {
        return $this->service->getTag();
    }

    /**
     * @param int $total
     */
    public function setTotal(int $total): void
    {
        $this->total = $total;
    }


    /**
     * loadSources Method.
     *
     * @return void
     */
    private function loadSources(): void
    {
        $this->sources = [
            new BibleContentService(),
        ];

        $perService = ceil($this->total / count($this->sources));
        foreach ($this->sources as $source) {
            $source->setTotal($perService);
        }

        $this->service = array_shift($this->sources);
    }
}
