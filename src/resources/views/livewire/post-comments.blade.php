<?php use App\Http\Livewire\PostCommentsComponent; ?>

<div x-show="comments" class="my-2 px-2 pt-2 pb-3 border-top">

    @if (session()->has('error'))
        <div x-data="{open: true}"
             x-show="open"
            class="alert alert-danger alert-dismissible">
            <button @click="open=false" type="button" class="btn-close" data-bs-dismiss="alert"></button>
            {{ session('error') }}
        </div>
    @endif

    @if(!empty($comments))
        <ul class="list-group border-0 mb-4">
            @foreach($comments as $item)
                <li class="list-group-item border-0 border-bottom pt-1">
                    <div class="text-black-50 small"><small>{{ $item['date'] }}</small></div>
                    <div class="mt-3">
                        <p class="text-black-50">{{ $item['comment'] }}</p>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif

    @error('comment')
        <div x-data="{open: true}"
             x-show="open"
            class="alert alert-dismissible alert-secondary mb-2">
            <button @click="open=false" type="button" class="btn-close" data-bs-dismiss="alert"></button>
            {{ $message }}
        </div>
    @enderror

    <label class="small mb-2" for="comment-post-{{ $postId }}">Enter Comment</label>
    <textarea
        wire:model.defer="comment"
        class="form-control"
        name="comment"
        id="comment-post-{{ $postId }}"
        cols="30"
        rows="5"
        minlength="3"
        required></textarea>
    <div class="d-flex justify-content-end">
        <button
            wire:click="commented"
            type="button"
            class="btn btn-primary btn-sm mt-3">
                Save
        </button>
    </div>
</div>
