@extends('layouts.app')

@section('content')

    <div class="container mt-2 mb-3">
        <div class="d-flex justify-content-center row">
            <div class="col-md-8">

                <x-post-card :post="$post" id="post-{{ $post['id'] }}" />

            </div>
        </div>
    </div>

@endsection
