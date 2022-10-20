<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Component;
use Spatie\Tags\Tag;

class PostTagsComponent extends Component
{
    public int $postId = 0;

    public string $search = "";

    public Collection $tags;

    public Collection $tagList;

    public bool $editTags = false;

    public int $selectedIndex = -1;

    protected array $rules = [
        'search' => 'required|string|min:2'
    ];

    /**
     * mount Method.
     *
     * @return void
     */
    public function mount(): void
    {
        $this->tagList = collect([]);
    }

    /**
     * updatedSearch Method.
     *
     * @param $value
     * @return void
     */
    public function updatedSearch($value): void
    {
        if (strlen($value) <= 1) {
            $this->resetTagList();
            return;
        }

        $this->validate();

        $this->tagList = Tag::select('name')
            ->containing($value)
            ->limit(10)
            ->get()
            ->pluck('name')
            ->sort();
    }

    /**
     * resetTagList Method.
     *
     * @return void
     */
    public function resetTagList(): void
    {
        $this->tagList = collect([]);
    }

    /**
     * addTag Method.
     *
     * @param string $value
     * @return void
     */
    public function addTag(string $value = ""): void
    {
        if ($this->selectedIndex != -1) {
            $value = $this->tagList[$this->selectedIndex];
        }

        if (empty(trim($value))) {
            $this->validate();
            $value = $this->search;
        }

        $tag = Str::of($value)
            ->trim()
            ->lower()
            ->replace([",", "'", '"', "|", "*", "!", "`"], "")
            ->stripTags()
            ->toString();

        $post = Post::find($this->postId);
        if (empty($post)) {
            session()->flash("error", "Post not found with Id: $this->postId");
            return;
        }

        $post->attachTag($tag);
        $post->status = 1;
        $post->save();

        $this->clearCache();
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

        $this->clearCache();
        $this->loadTags($post);
        $this->reset();
    }

    /**
     * increment Method.
     *
     * @return void
     */
    public function increment(): void
    {
        if ($this->selectedIndex == $this->tagList->count() - 1) {
            $this->selectedIndex = 0;
            return;
        }

        $this->selectedIndex++;
    }

    /**
     * decrement Method.
     *
     * @return void
     */
    public function decrement(): void
    {
        if ($this->selectedIndex == 0) {
            $this->selectedIndex = $this->tagList->count() - 1;
            return;
        }

        $this->selectedIndex--;
    }

    /**
     * cancel Method.
     *
     * @return void
     */
    public function cancel(): void
    {
        $this->reset();
        $this->editTags = false;
    }

    /**
     * reset Method.
     *
     * @param ...$properties
     * @return void
     */
    public function reset(...$properties): void
    {
        $this->search = "";
        $this->selectedIndex = -1;
        $this->resetTagList();
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
        $this->tags = $post->tags()->pluck('name')->sort();
    }

    private function clearCache()
    {
        // TODO clear Query caches for tags and tagged
        // TODO clear List cache for tags
    }
}
