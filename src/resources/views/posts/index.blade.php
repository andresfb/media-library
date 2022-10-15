@extends('layouts.app')

@section('content')

    <div class="container mt-2 mb-3">
        <div class="d-flex justify-content-center row">
            <div class="col-md-8">
                <div class="feed">

                @forelse($posts as $post)

                    <div class="bg-white border mt-2">

                        <div>
                            <div class="d-flex flex-row justify-content-between align-items-center p-2 border-bottom">
                                <div class="d-flex flex-row align-items-center feed-text px-1">
                                    <img class="rounded-circle" src="{{ $post['avatar'] }}" width="45" alt="avatar">
                                    <div class="d-flex flex-column flex-wrap">
                                        <span class="fw-semibold mx-2">{{ $post['name'] }}</span>
                                        <span class="text-black-50 time mx-2">{{ $post['date'] }} ago</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="feed-image my-2 px-2 pb-2 border-bottom">
                        @if ($post['type'] == 'video')
                            <div>show video</div>
                        @else
                            <img class="img-fluid img-responsive" src="{{ $post['media'] }}" alt="media">
                        @endif
                        </div>

                        <div class="my-3 px-2">
                            <span class="fw-semibold">{{ $post['title'] }}</span>
                        </div>

                        <div class="my-2 px-2 pb-1">
                            <span>{!! $post['content'] !!}</span>
                        </div>

                        <div class="my-2 px-2 pb-2 border-bottom">
                        @foreach($post['tags'] as $tag)
                            <span class="badge rounded-pill text-bg-light fw-light small @if (!$loop->first) 'ml-2' @endif">
                                <a href="#" class="tag-link text-decoration-none text-black-50">{{ $tag['tag'] }}</a>
                            </span>
                        @endforeach
                        </div>

                        <div class="d-flex justify-content-end socials p-1 py-2">
                            <i class="fa fa-comments-o"></i>
                            <a href="#" class="badge rounded-pill text-decoration-none text-bg-light fw-light text-black-50 small">Extra Info</a>
                        </div>

                        <div class="d-none my-2 px-2 pt-2 pb-3 border-top">
                            <label class="small mb-1" for="comment-post-{{ $post['id'] }}">Comment</label>
                            <textarea class="form-control" name="comment" id="comment-post-{{ $post['id'] }}" cols="30" rows="5"></textarea>
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-primary btn-sm mt-3">Save</button>
                            </div>
                        </div>

                        <div class="d-none my-2 px-2 p-2 border-top small">
                        @foreach($post['extra_info'] as $key => $info)
                            <div><small><span class="fw-semibold">{{ ucfirst($key) }}:</span> {{ $info }}</small></div>
                        @endforeach
                        </div>
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
    </div>

@endsection
