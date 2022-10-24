@extends('layouts.app')

@section('content')

    <div class="container mt-2 mb-3">

        <div class="d-flex justify-content-center row">
            <div class="col-md-8">
                <div class="feed">

                @forelse($posts as $post)
                    <x-post-card :post="$post" id="post-{{ $post['id'] }}" />
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
