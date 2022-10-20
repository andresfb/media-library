<?php

namespace App\Http\Controllers;

use App\Models\Feed;
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
    public function index(Request $request)
    {
        $values = $request->validate([
            'type' => ['nullable', 'string', Rule::in(['image', 'video'])]
        ]);

        $query = Feed::pending();

        if (!empty($values['type'])) {
            $query->where('posts.type', $values['type']);
        }

        return view(
            'posts.index',
            ['posts' => $query->get()]
        );
    }
}
