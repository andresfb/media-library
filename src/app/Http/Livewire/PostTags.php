<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Component;
use Spatie\Tags\Tag;

class PostTags extends Component
{
    public int $postId = 0;

    public string $search = "";

    public Collection $tags;

    public Collection $tagList;

    public bool $editTags = false;

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
            ->pluck('name');
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
        if (empty(trim($value))) {
            $this->validate();
            $value = $this->search;
        }

        $tag = Str::of($value)
            ->trim()
            ->lower()
            ->headline()
            ->replace([",", "'", '"', "|", "*", "!", "`"], "")
            ->stripTags()
            ->toString();

        $post = Post::find($this->postId);
        if (empty($post)) {
            session()->flash("error", "Post not found with Id: $this->postId");
            return;
        }

        $post->attachTag($tag);
        $post->status = true;
        $post->save();

        $this->loadTags($post);
        $this->search = "";
        $this->resetTagList();
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
            ->headline()
            ->replace([",", "'", '"', "|", "*", "!", "`"], "")
            ->stripTags()
            ->toString();

        $post->detachTag($tag);

        $this->loadTags($post);
        $this->search = "";
        $this->resetTagList();
    }

    /**
     * cancel Method.
     *
     * @return void
     */
    public function cancel(): void
    {
        $this->search = "";
        $this->resetTagList();
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
        $this->tags = $post->tags()->pluck('name')->sort();
    }
}
