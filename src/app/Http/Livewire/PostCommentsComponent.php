<?php

namespace App\Http\Livewire;

use App\Models\Comment;
use App\Models\Feed;
use App\Models\Post;
use App\Traits\PostFeedFindable;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Component;

class PostCommentsComponent extends Component
{
    use PostFeedFindable;

    public int $postId = 0;

    public array $comments;

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

        $result = $this->getModels($this->postId);
        if (!$result) {
            return;
        }

        [$post, $feed] = $result;

        $post->comments()->create([
            'comment' => $comment
        ]);

        $feed->push('comments', $comment, true);
        $feed->save();

        $this->comment = "";

        $this->comments = $post->comments->map(function (Comment $comment) {
            return [
                'date' => $comment->created_at->longAbsoluteDiffForHumans(),
                'comment' => $comment->comment,
            ];
        })->toArray();
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
