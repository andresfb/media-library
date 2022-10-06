<?php

namespace App\Services;

use App\Services\Content\BibleContentService;
use App\Services\Content\ContentServiceInterface;
use App\Services\Content\QuoteContentService;

class ContentOrchestratorService
{
    private int $total;

    private array $sources = [];

    private ?ContentServiceInterface $service = null;

    private bool $loaded = false;

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
        if (!$this->loaded) {
            $this->loadSources();
        }

        if ($this->service->next()) {
            return true;
        }

        $this->service = array_shift($this->sources);
        if (empty($this->service)) {
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
     * markUsed Method.
     *
     * @return void
     */
    public function markUsed(): void
    {
        $this->service->markUsed();
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
            new QuoteContentService(),
        ];

        // Count how many records we have in all sources
        $totalRecords = 0;
        /** @var ContentServiceInterface $source */
        foreach ($this->sources as $source) {
            $totalRecords += $source->getTotal();
        }

        // Get the total random records each source will generate
        // proportional of how many it has
        foreach ($this->sources as $source) {
            $perService = ceil($this->total * ($source->getTotal() / $totalRecords));
            $source->loadRecords($perService);
        }

        $this->service = array_shift($this->sources);
        $this->loaded = true;
    }
}
