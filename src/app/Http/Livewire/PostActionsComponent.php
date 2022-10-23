<?php

namespace App\Http\Livewire;

use App\Traits\PostFeedFindable;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Livewire\Component;

class PostActionsComponent extends Component
{
    use PostFeedFindable;

    public int $postId = 0;


    /**
     * disable Method.
     *
     * @return RedirectResponse|void
     */
    public function disable()
    {
        $result = $this->getModels($this->postId);
        if (!$result) {
            return;
        }

        [$post, $feed] = $result;

        $post->status = 2;
        $post->save();

        $post->item()->update(['active' => 0]);
        $post->delete();

        $feed->status = 2;
        $feed->save();
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
