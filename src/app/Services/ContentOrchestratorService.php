<?php

namespace App\Services;

use Illuminate\Foundation\Testing\WithFaker;

class ContentOrchestratorService
{
    use WithFaker;

    /** @var int */
    private int $total;

    public function __construct()
    {
        $this->setUpFaker();
        $this->total = (int) config('items.max_random_posts');
    }

    /**
     * next Method.
     *
     * @return void
     */
    public function next(): void
    {

    }

    /**
     * getTitle Method.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->faker->sentence(3);
    }

    /**
     * getText Method.
     *
     * @return string
     */
    public function getText(): string
    {
        return $this->faker->paragraph(4);
    }

    /**
     * getTag Method.
     *
     * @return string
     */
    public function getTag(): string
    {
        return "faker";
    }

    /**
     * @param int $total
     */
    public function setTotal(int $total): void
    {
        $this->total = $total;
    }
}
