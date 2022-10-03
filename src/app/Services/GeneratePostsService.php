<?php

namespace App\Services;

use App\Models\Item;
use App\Models\Post;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GeneratePostsService
{
    private int $generated = 0;

    private ContentOrchestratorService $service;


    public function __construct(ContentOrchestratorService $service)
    {
        $this->service = $service;
    }

    /**
     * execute Method.
     *
     * @param int $howMany
     * @return void
     */
    public function execute(int $howMany = 0): void
    {
        if (empty($howMany)) {
            $howMany = (int) config('items.max_random_posts');
        }

        Log::info("Generating started. Generating ($howMany) Posts.");
        $this->service->setTotal($howMany);

        $items = Item::select("items.*")
            ->unused($howMany)
            ->get();

        $this->generatePosts($items);
        Log::info("Generating Posts finished at Generated {$this->generated}");
    }

    /**
     * generatePosts Method.
     *
     * @param $items
     * @return void
     */
    private function generatePosts($items): void
    {
        if (!$items->count()) {
            Log::info("Generating Posts - No Post found.");
            return;
        }

        foreach ($items as $item) {
            try {
                if (!$this->service->next()) {
                    break;
                }

                $title = $this->service->getTitle();
                $slug = Str::slug($title);

                $post = Post::create([
                    'item_id' => $item->id,
                    'status' => 0,
                    'type' => $item->type,
                    'slug' => $slug,
                    'title' => $title,
                    'content' => $this->service->getText(),
                    'og_file' => "/$item->og_path/$item->og_file"
                ]);

                $post->attachTag($this->service->getTag());

                $item->events()->updateOrCreate([
                    'item_id' => $item->id,
                    'action' => 'posted'
                ], [
                    'requester' => GeneratePostsService::class
                ]);

                $this->generated++;
            } catch (Exception $e) {
                Log::error($e->getMessage());
            }
        }
    }
}
