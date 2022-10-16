<?php

namespace App\Http\Controllers;

use App\Services\PostsService;
use App\ViewModels\PostListViewModel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PostsController extends Controller
{
    /**
     * index Method.
     *
     * @return Application|Factory|View
     */
    public function index(Request $request, PostsService $service)
    {
        $values = $request->validate([
            'type' => ['nullable', 'string', Rule::in(['image', 'video'])]
        ]);

        return view(
            'posts.index',
            new PostListViewModel($service->getLatest($values))
        );
    }
}
