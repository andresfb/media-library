<?php

namespace App\Http\Livewire;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Component;
use Spatie\Tags\Tag;

abstract class BaseSearchTagComponent extends Component
{
    public string $search = "";

    public Collection $tagList;

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
     * resetTagList Method.
     *
     * @return void
     */
    public function resetTagList(): void
    {
        $this->tagList = collect([]);
    }

    /**
     * parseSearch Method.
     *
     * @param string $value
     * @return string
     */
    public function parseSearch(string $value): string
    {
        if ($this->selectedIndex != -1) {
            $value = $this->tagList[$this->selectedIndex];
        }

        if (empty(trim($value))) {
            $this->validate();
            $value = $this->search;
        }

        return Str::of($value)
            ->trim()
            ->lower()
            ->replace([",", "'", '"', "|", "*", "!", "`"], "")
            ->stripTags()
            ->toString();
    }
}
