<div class="my-2 px-2 pb-3">

    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

@if ($editTags)
    <div class="mt-3">
        <input
            class="form-control form-control-sm no-shadow"
            placeholder="Search"
            type="search"
            wire:keydown.escape="$set('search', '')"
            wire:keydown.enter="addTag"
            wire:model.debounce.300ms="search">

    @if (!empty($tagList))
        <div class="z-40 position-absolute mt-2 col-lg-6">
            <ul class="list-group">
            @foreach($tagList as $tag)
                <li wire:click="addTag('{{ $tag }}')"
                    class="list-group-item list-group-item-action">
                    {{ $tag }}
                </li>
            @endforeach
            </ul>
        </div>
    @endif

        <div class="mt-3">
            @foreach($tags as $tag)
                <span class="badge rounded-pill text-bg-secondary fw-semibold mr-2">
                    <a href="#"
                       wire:click.prevent="deleteTag('{{ $tag }}')"
                       class="tag-link text-decoration-none text-white">
                        {{ $tag }}
                        <i class="fas fa-ban"></i>
                    </a>
                </span>
            @endforeach
            <span class="badge rounded-pill text-bg-warning fw-semibold">
                <a href="#" wire:click.prevent="cancel" class="tag-link text-decoration-none text-white">Cancel</a>
            </span>
        </div>
    </div>
@endif

@if (!$editTags)
    <div class="mt-3">
        @foreach($tags as $tag)
            <span class="badge rounded-pill text-bg-info fw-semibold mr-2">
                <a href="#" class="tag-link text-decoration-none text-white">{{ $tag }}</a>
            </span>
        @endforeach
        <span class="badge rounded-pill text-bg-success fw-semibold">
            <a href="#" wire:click.prevent="$toggle('editTags')" class="tag-link text-decoration-none text-white">Edit Tags</a>
        </span>
    </div>
@endif

</div>
