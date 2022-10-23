<?php

namespace App\Http\Livewire;

use App\Traits\TagsCacheable;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;

class TaggedTagsComponent extends BaseSearchTagComponent
{
    use TagsCacheable;

    public Collection $selected;

    public Collection $tags;


    /**
     * mount Method.
     *
     * @return void
     */
    public function mount(): void
    {
        parent::mount();
        $this->tags = $this->getAllTags();
    }

    /**
     * select Method.
     *
     * @return RedirectResponse|void
     */
    public function select(string $value = "")
    {
        $tag = $this->parseSearch($value);

        if (!$this->tags->contains($tag)) {
            session()->flash("error", "Tag {$tag} not found");
            return;
        }

        $tags = $this->selected->add($tag);
        $selected = $tags->implode(",");

        return redirect()->route(
            'tagged',
            ['tags' => $selected]
        );
    }

    /**
     * render Method.
     *
     * @return Application|Factory|View
     */
    public function render(): View|Factory|Application
    {
        return view('livewire.tagged-tags-component');
    }
}
