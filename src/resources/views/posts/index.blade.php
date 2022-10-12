@extends('layouts.app')

@section('content')

    @forelse($posts as $post)

        <div><img src="{{ $post['avatar'] }}" alt="avatar"></div>
        <div><strong>{{ $post['name'] }}</strong></div>

        <div><strong>{{ $post['title'] }}</strong></div>

        @if ($post['type'] == 'video')
            <div>show video</div>
        @else
            <div><img src="{{ $post['media'] }}" alt="media"></div>
        @endif

        <div>{!! $post['content'] !!}</div>

        @foreach($post['tags'] as $tag)
            <a href="#{{ $tag['slug'] }}">{{ $tag['tag'] }}</a>,
        @endforeach

        <div>
            Original Location: {{ $post['og_location'] }} <br>
            Original file name: {{ $post['og_file_name'] }} <br>
        </div>

        <hr>
    @empty
        <h2>No Posts found</h2>
    @endforelse

@endsection
