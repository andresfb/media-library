<?php

namespace App\Http\Livewire;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Component;

class PostCommentsComponent extends Component
{
    public int $postId = 0;

    public Collection $comments;

    public string $comment = "";

    protected array $rules = [
        'comment' => 'required|string|min:3'
    ];

    /**
     * updatedComment Method.
     *
     * @return void
     */
    public function commented(): void
    {
        $values = $this->validate();

        $comment = Str::of($values['comment'])
                ->trim()
                ->stripTags()
                ->toString();

        $post = Post::find($this->postId);
        if (empty($post)) {
            session()->flash("error", "Post not found with Id: $this->postId");
            return;
        }

        $post->comments()->create([
            'comment' => $comment
        ]);

        $this->comment = "";

        $this->comments = $post->comments->map(function (Comment $comment) {
            return [
                'date' => $comment->created_at->longAbsoluteDiffForHumans(),
                'comment' => $comment->comment,
            ];
        });
    }

    /**
     * render Method.
     *
     * @return Factory|View|Application
     */
    public function render(): Factory|View|Application
    {
        return view('livewire.post-comments');
    }
}
