@extends('layouts.app')

@section('content')

    <div class="container-fluid py-4 px-4">
        <div class="d-flex justify-content-center row">
            <div class="col bg-white border rounded">

                <h4 class="mt-3">List of all Tags</h4>

                <div class="row my-5">
                @forelse($tags as $tag)
                    <div class="col-sm-6 col-md-4 col-lg-3 col-lg-2 py-2">
                        <a href="{{ route('tagged', ['tags' => $tag]) }}"
                           class="btn btn-outline-dark">{{ $tag }}</a>
                    </div>
                @empty
                    <h6>No Tags found</h6>
                @endforelse
                </div>

            </div>
        </div>
    </div>

@endsection
