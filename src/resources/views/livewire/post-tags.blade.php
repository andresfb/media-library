<?php use App\Http\Livewire\PostTagsComponent; ?>

<div class="position-relative my-2 px-2 pb-3">

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            {{ session('error') }}
        </div>
    @endif

@if ($editTags)
    <div class="mt-3">
        <input
            x-ref="searchInput"
            class="form-control form-control-sm no-shadow"
            type="text"
            wire:keydown.escape="$set('search', '')"
            wire:keydown.enter="addTag"
            wire:keydown.arrow-up="decrement"
            wire:keydown.arrow-down="increment"
            wire:model.debounce.500ms="search">

        <div wire:ignore x-init="() => $refs.searchInput.focus()"></div>

    @if (!empty($tagList))
        <div class="z-40 position-absolute border-top-0 col-lg-6">
            <ul class="list-group">
            @foreach($tagList as $tag)
                <li wire:click="addTag('{{ $tag }}')"
                    class="list-group-item
                        list-group-item-action
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

        <div class="mt-3">
            @foreach($tags as $tag)
                <span class="badge rounded-pill text-bg-secondary fw-semibold">
                    <a href="#"
                       wire:click.prevent="deleteTag('{{ $tag }}')"
                       class="tag-link text-decoration-none text-white p-1 me-1">
                        {{ $tag }}
                        <i class="fas fa-ban"></i>
                    </a>
                </span>
            @endforeach
            <span class="badge rounded-pill text-bg-warning fw-semibold">
                <a href="#"
                   wire:click.prevent="cancel"
                   class="tag-link text-decoration-none text-white p-2 me-1">Cancel</a>
            </span>
        </div>
    </div>
@endif

@if (!$editTags)
    <div class="mt-3">
        @foreach($tags as $tag)
            <span class="h6 me-1">
                <a href="{{ route('tagged',['tags' => $tag]) }}" class="tag-link">{{ $tag }}</a>
            </span>
        @endforeach
        <span class="badge rounded-pill text-bg-success fw-semibold ms-2">
            <a href="#"
               wire:click.prevent="$toggle('editTags')"
               class="tag-link text-decoration-none text-white p-2">Edit</a>
        </span>
    </div>
@endif

</div>
