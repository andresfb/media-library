<?php

namespace App\Http\Controllers;

use App\Services\PostsService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class PostsController extends Controller
{
    /**
     * index Method.
     *
     * @return Application|Factory|View
     */
    public function index(PostsService $service)
    {
        return view('posts.index', $service->getLatest());
    }
}
