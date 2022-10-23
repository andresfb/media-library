<?php

namespace App\Http\Livewire;

use App\Models\Post;
use App\Traits\PostFeedFindable;
use App\Traits\TagsCacheable;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Component;
use Spatie\Tags\Tag;

class PostTagsComponent extends BaseSearchTagComponent
{
    use PostFeedFindable, TagsCacheable;

    public int $postId = 0;

    public array $tags;

    public bool $editTags = false;


    /**
     * addTag Method.
     *
     * @param string $value
     * @return void
     */
    public function addTag(string $value = ""): void
    {
        $tag = $this->parseSearch($value);

        $result = $this->getModels($this->postId);
        if (!$result) {
            return;
        }

        [$post, $feed] = $result;

        $post->attachTag($tag);
        $post->status = 1;
        $post->save();

        $feed->status = 1;
        $feed->push('tags', $tag, true);
        $feed->save();

        $this->loadTags($post);
        $this->reset();
    }

    /**
     * deleteTag Method.
     *
     * @param string $value
     * @return void
     */
    public function deleteTag(string $value): void
    {
        if (empty(trim($value))) {
            session()->flash("error", "Missing tag");
            return;
        }

        $post = Post::find($this->postId);
        if (empty($post)) {
            session()->flash("error", "Post not found with Id: $this->postId");
            return;
        }

        $tag = Str::of($value)
            ->trim()
            ->lower()
            ->replace([",", "'", '"', "|", "*", "!", "`"], "")
            ->stripTags()
            ->toString();

        $post->detachTag($tag);

        $this->loadTags($post);
        $this->reset();
    }

    /**
     * cancel Method.
     *
     * @return void
     */
    public function cancel(): void
    {
        $this->clearCache();
        $this->reset();
        $this->editTags = false;
    }

    /**
     * render Method.
     *
     * @return Application|Factory|View
     */
    public function render(): View|Factory|Application
    {
        return view('livewire.post-tags');
    }


    /**
     * loadTags Method.
     *
     * @param Post $post
     * @return void
     */
    private function loadTags(Post $post): void
    {
        $this->tags = $post->tags()
            ->pluck('name')
            ->sort()
            ->toArray();
    }
}
