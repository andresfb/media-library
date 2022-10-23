@extends('layouts.app')

@section('content')
    <div class="container-fluid p-sm-3 p-md-4 mt-2">

        <section>
            <h4>List of Tagged Posts</h4>
        </section>

        <section
            x-data="{search: false}"
            class="mt-4 pt-1">
            <h5>
                Selected Tags
            </h5>
            <div class="mt-1 col-lg-6">
                @forelse($selected as $tag)
                    <span class="me-3">
                        <a class="h6 me-1" href="{{ route('tagged', ['tags' => $tag]) }}">{{ $tag }}</a>
                        <a href="{{ route('tagged', ['tags' => $removeTag($tag)]) }}"
                           class="text-decoration-none text-black-50">
                            <i class="fa-regular fa-rectangle-xmark"></i>
                        </a>
                    </span>
                @empty
                    <span class="h6">No tags selected</span>
                @endforelse
                <span class="ms-3">
                    <a href="#" x-on:click.prevent="search=!search" class="text-decoration-none text-black-50 me-2">
                       <i class="fas fa-search-plus"></i>
                    </a>
                    <a href="{{ route('tagged') }}" class="text-decoration-none text-black-50">
                        <i class="fa-solid fa-rotate"></i>
                    </a>
                </span>
            </div>

            <div x-show="search" class="pt-1 mt-4 row">
                <livewire:tagged-tags-component
                    :selected="$selected" />
            </div>

        </section>

        <div class="container-fluid pt-3 mt-4">
            <div class="row mt-2">
                <div class="col">
                    <h6>Found {{ $postCount }} Posts</h6>
                </div>
            </div>

            <div x-data="{}" class="row mt-2">
            @forelse($posts as $post)
                <div class="col-sm-6 col-md-4 col-lg-3 p-3 bg-white">
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
                        <a href="{{ $post['media'] }}" target="_blank">
                            <img class="img-fluid img-responsive" src="{{ $post['media'] }}" alt="media">
                        </a>
                    @endif
                        <figcaption class="mt-2">
                        @foreach($post['tags'] as $tag)
                            <span class="h6 me-3">
                                <a href="{{ route('tagged',['tags' => $tag]) }}" class="tag-link m-1">{{ $tag }}</a>
                                <a href="{{ route('tagged', ['tags' => $addTag($tag)]) }}"
                                   class="text-decoration-none">
                                    <i class="fa-regular fa-square-plus"></i>
                                </a>
                            </span>
                        @endforeach
                            <a href="#" @click.prevent="return" class="ms-2" target="_blank"><i class="fa-solid fa-lg fa-pen-to-square"></i></a>
                        </figcaption>
                    </figure>
                </div>
            @empty
                <div class="col">
                    <h5>Not Post Found</h5>
                </div>
            @endforelse

            </div>

            <div class="container justify-content-center py-5 px-2">
                <div class="row">
                    <div class="col">
                        {{ empty($postList) ? '' : $postList->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
