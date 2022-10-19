<?php

namespace App\Http\Controllers;

use App\Services\PostsService;
use App\ViewModels\TaggedPostListViewModel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaggedPostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(Request $request, PostsService $service)
    {
        $values = $request->validate([
            'tags' => ['nullable', 'string'],
            'type' => ['nullable', 'string', Rule::in(['image', 'video'])]
        ]);

        [$posts, $tags, $count] = $service->getTagged($values);

        return view(
            'tagged.index',
            new TaggedPostListViewModel($posts, $tags, $count)
        );
    }
}
