@extends('layouts.app')

@section('content')

    <div class="container mt-2 mb-3">
        <div class="d-flex justify-content-center row">
            <div class="col-md-8">
                <div class="feed">

                @forelse($posts as $post)

                    <div class="bg-white border mt-2 mb-3" x-data="{
                        comments: false,
                        info: false,
                        actions: false
                    }">

                        <!-- Poster -->
                        <div class="d-flex flex-row justify-content-between align-items-center p-2 border-bottom">
                            <div class="d-flex flex-row align-items-center feed-text px-1">
                                <img class="rounded-circle" src="{{ $post['avatar'] }}" width="45" alt="avatar">
                                <div class="d-flex flex-column flex-wrap">
                                    <span class="fw-semibold mx-2">{{ $post['name'] }}</span>
                                    <span class="text-black-50 time mx-2">{{ $post['date'] }} ago</span>
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
                        <div class="d-flex justify-content-end socials p-1 py-2">
                            <i @click="comments=!comments" class="fa fa-comments @if($post['comments']->isNotEmpty()) text-black-50 @endif"></i>
                            <i @click="info=!info" class="fas fa-info-circle @if(!empty($post['extra_info'])) text-black-50 @endif"></i>
                            <i @click="actions=!actions" class="fa fa-cogs"></i>
                        </div>

                        <!-- Comments -->
                        <livewire:post-comments-component
                            :comments="$post['comments']"
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

                    @if ($loop->remaining == 3)
                        <!-- flag to trigger the load of the next 20 posts -->
                        <div id="loader" class="d-none"></div>
                    @endif
                @empty
                    <h2>No Posts found</h2>
                @endforelse

                </div>
            </div>
        </div>
        <div class="d-flex justify-content-center row pt-2 pb-4">
            <div class="col-md-8">
                <a class="btn btn-light col-12" href="{{ route('home') }}">
                    Refresh
                </a>
            </div>
        </div>
    </div>

@endsection
