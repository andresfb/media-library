<div class="col-lg-4 col-xl-3">

    <label class="h6" for="tag-search">Add to filter</label>
    <input id="tag-search"
           wire:keydown.escape="$set('search', '')"
           wire:keydown.enter="select"
           wire:keydown.arrow-up="decrement"
           wire:keydown.arrow-down="increment"
           wire:model.debounce.500ms="search"
           class="form-control mb-1"
           type="search"
           name="search"
           placeholder="Search Tag..."
           required>
    @error('search') <span class="text-danger">{{ $message }}</span> @enderror
    @if (session()->has('error'))
        <span class="text-danger">{{ session('error') }}</span>
    @endif

    <!-- Search results list -->
    @if (!empty($tagList))
        <div class="z-40 position-absolute border-top-0">
            <ul class="list-group">
                @foreach($tagList as $tag)
                    <li wire:click="select('{{ $tag }}')"
                        class="list-group-item list-group-item-action me-2
                    @if($loop->index == $selectedIndex)
                        bg-light
                    @endif
                    ">
                        {{ $tag }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

</div>
