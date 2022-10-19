@extends('layouts.app')

@section('content')
    <div class="container-fluid p-sm-3 px-md-5 py-md-4">

        <section>
            <h4>List of Tagged Posts</h4>
        </section>

        <section
            x-data="{search: false}"
            class="mt-4">
            <h5>
                Used Tags
            </h5>
            <div class="mt-1 col-lg-6">
                @forelse($selected as $tag)
                    <span class="me-3">
                        <a class="h6" href="{{ route('tagged',['tags' => $tag]) }}">{{ $tag }}</a>
                    </span>
                @empty
                    <span class="h6">No tags selected</span>
                @endforelse
                <span class="ms-3">
                   <a href="#" x-on:click.prevent="search=!search" class="text-black-50"><i class="fas fa-search-plus"></i></a>
                </span>
            </div>

            <div x-show="search" class="pt-1 mt-4 row">
                <div class="col-lg-4 col-xl-3">
                    <label class="h6" for="tag-search">Add to filter</label>
                    <input id="tag-search" class="form-control" type="search" placeholder="Search Tag...">
                </div>
            </div>

        </section>

        <div class="container-fluid pt-3 mt-4">
            <div class="row mt-2">
                <div class="col">
                    <h6>Found {{ $postCount }} Posts</h6>
                </div>
            </div>

            <div class="row mt-2">
            @forelse($posts as $post)
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <figure>
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
                        <figcaption class="mt-1">
                        @foreach($post['tags'] as $tag)
                            <span class="h6 me-1">
                                <a href="{{ route('tagged',['tags' => $tag]) }}" class="tag-link">{{ $tag }}</a>
                            </span>
                        @endforeach
                        </figcaption>
                    </figure>
                </div>
            @empty
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <h5>Not Post Found</h5>
                </div>
            @endforelse

            </div>
        </div>

    </div>
@endsection
