<div x-show="card"
     class="bg-white border mt-2 mb-3" x-data="{
         comments: false,
         info: false,
         actions: false,
         card: true
     }">

    <!-- Poster -->
    <div class="d-flex flex-row justify-content-between align-items-center p-2 border-bottom">
        <div class="d-flex flex-row align-items-center feed-text px-1">
            <img class="rounded-circle" src="{{ $post['avatar'] }}" width="45" alt="avatar">
            <div class="d-flex flex-column flex-wrap">
                <span class="fw-semibold mx-2">{{ $post['name'] }}</span>
                <span class="text-black-50 time mx-2">{{ $post['date']->longAbsoluteDiffForHumans() }} ago</span>
            </div>
        </div>
    </div>

    <!-- Media -->
    <div class="feed-image my-2 px-2 pb-2 border-bottom">
        @if ($post['type'] == 'video')
            <div class="ratio ratio-{{ $post['aspect'] }}">
                <video @if(!empty($post['poster'])) poster="{{ $post['poster'] }}" @endif
                loop controls>
                    <source src="{{ $post['media'] }}" type="{{ $post['mime_type'] }}">
                    Video is not supported
                </video>
            </div>
        @else
            <img class="img-fluid img-responsive" src="{{ $post['media'] }}" alt="media">
        @endif
    </div>

    <!-- Tags -->
    <livewire:post-tags-component
        :tags="$post['tags']"
        :postId="$post['id']"
        wire:key="post-tags-{{ $post['id'] }}" />

    <!-- Post title -->
    <div class="my-3 px-2">
        <span class="fw-semibold">{{ $post['title'] }}</span>
    </div>

    <!-- Post text -->
    <div class="my-2 px-2 pb-1">
        <span>{!! $post['content'] !!}</span>
    </div>

    <!-- Buttons -->
    <div class="d-flex justify-content-start px-1 py-2 ms-3 mb-2">
        <a href="#"
           @click.prevent="comments=!comments"
           class="text-muted">
            <i class="fa-regular fa-lg fa-comments me-5"></i>
        </a>
        <a href="#"
           @click.prevent="info=!info"
           class="text-muted">
            <i class="fa-solid fa-lg fa-circle-info me-5"></i>
        </a>
        <a href="#"
           @click.prevent="actions=!actions"
           class="text-muted">
            <i class="fa-solid fa-lg fa-sliders me-5"></i>
        </a>
        <a href="#"
           @click.prevent="card=false"
           class="text-muted">
            <i class="fa-solid fa-lg fa-eye-slash me-5"></i>
        </a>
    </div>

    <!-- Comments -->
    <livewire:post-comments-component
        :comments="[]"
        :postId="$post['id']"
        wire:key="post-comments-{{ $post['id'] }}" />

    <!-- Extra Info -->
    <div x-show="info" class="my-2 px-2 p-2 border-top small">
        @foreach($post['extra_info'] as $key => $info)
            @if(is_array($info))
                @foreach($info as $ikey => $data)
                    <div><small><span class="fw-semibold">{{ ucfirst($ikey) }}:</span> {{ $data }}</small></div>
                @endforeach
            @else
                <div><small><span class="fw-semibold">{{ ucfirst($key) }}:</span> {{ $info }}</small></div>
            @endif
        @endforeach
    </div>

    <!-- Actions -->
    <livewire:post-actions-component
        :postId="$post['id']"
        wire:key="post-actions-{{ $post['id'] }}" />

</div>
