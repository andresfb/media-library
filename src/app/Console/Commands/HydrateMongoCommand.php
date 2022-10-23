<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Services\GenerateFeedService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class HydrateMongoCommand extends Command
{
    protected $signature = 'hydrate:mongo';

    protected $description = 'Feed the MongoDB with all the used Posts. Plus an extra 200';

    private GenerateFeedService $service;


    public function __construct(GenerateFeedService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    /**
     * handle Method.
     *
     * @return int
     */
    public function handle(): int
    {
        try {
            Post::select('posts.*')
                ->whereUsed(true)
                ->with('tags')
                ->with('item')
                ->with('item.media')
                ->with('comments')
                ->chunk(200, function (Collection $posts) {
                    $this->newLine();
                    $this->warn("Saving {$posts->count()} Posts\n");
                    $this->service->generateFeed($posts, true);
                    $this->newLine();
                    usleep(700);
                });

            $this->newLine();
            $this->info("Generating the extra 200 posts");
            $this->newLine();
            $this->service->execute(200);

            $this->newLine();
            $this->info("Done");
            return 0;
        } catch (Exception $e) {
            $this->newLine();
            $this->warn("Error found");
            $this->error($e->getMessage());
            $this->newLine();
            return 1;
        }
    }
}
