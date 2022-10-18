<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Livewire\Component;

class PostActionsComponent extends Component
{
    public int $postId = 0;

    /**
     * disable Method.
     *
     * @return RedirectResponse|void
     */
    public function disable()
    {
        $post = Post::find($this->postId);
        if (empty($post)) {
            session()->flash("error", "Post not found with Id: $this->postId");
            return;
        }

        $post->status = 2;
        $post->save();

        $post->item()->update(['active' => 0]);
        $post->delete();

        return redirect()->route('home');
    }

    /**
     * render Method.
     *
     * @return Application|Factory|View
     */
    public function render(): View|Factory|Application
    {
        return view('livewire.post-actions');
    }
}
