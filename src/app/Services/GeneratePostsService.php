<?php

namespace App\Services;

use App\Models\Item;
use App\Models\Post;
use Exception;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GeneratePostsService
{
    use WithFaker;

    /** @var int */
    private int $howMany;

    /** @var int */
    private int $generated = 0;


    public function __construct(int $howMany = 0)
    {
        $this->setUpFaker();
        $this->howMany = $howMany ?? (int) config('items.max_random_posts');
    }

    /**
     * execute Method.
     *
     * @return void
     */
    public function execute(): void
    {
        Log::info("Generating Posts started at " . now()->toDateTimeString());

        $items = Item::unused('image', $this->howMany)->get();
        $this->generatePosts($items, "image");

        $items = Item::unused('video', $this->howMany)->get();
        $this->generatePosts($items, "video");

        Log::info("Generating Posts started at " . now()->toDateTimeString() . ". Generated {$this->generated}");
    }

    /**
     * generatePosts Method.
     *
     * @param $items
     * @param string $type
     * @return void
     */
    private function generatePosts($items, string $type): void
    {
        if (!$items->count()) {
            Log::info("Generating Posts found no {$type} " . now()->toDateTimeString());
            return;
        }

        /** @var Item $item */
        foreach ($items as $item) {
            try {
                $title = $this->faker->sentence(4);
                $slug = Str::slug($title);

                Post::create([
                    'item_id' => $items->id,
                    'status' => 0,
                    'type' => $type,
                    'slug' => $slug,
                    'title' => $title,
                    'content' => $this->faker->realTextBetween(100, 500),
                    'og_file' => "/$items->og_path/$items->og_file"
                ]);

                $item->events()->updateOrCreate([
                    'item_id' => $item->id,
                    'action' => 'posted'
                ], [
                    'requester' => GeneratePostsService::class
                ]);
            } catch (Exception $e) {
                Log::error($e->getMessage());
            }
        }
    }
}
