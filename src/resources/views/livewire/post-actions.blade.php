<?php use App\Http\Livewire\PostActionsComponent; ?>

<div x-show="actions" class="my-2 px-2 pt-2 pb-3 border-top">

    @if (session()->has('error'))
        <div x-data="{open: true}"
             x-show="open"
             class="alert alert-danger alert-dismissible">
            <button @click="open=false" type="button" class="btn-close" data-bs-dismiss="alert"></button>
            {{ session('error') }}
        </div>
    @endif

    <div class="d-flex justify-content-end">
        <button
            x-on:click.prevent="if (confirm('Disable Post?')) $wire.disable()"
            type="button"
            class="btn btn-secondary btn-sm mt-3">Disable Item</button>
    </div>
</div>
