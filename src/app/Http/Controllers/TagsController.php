<?php

namespace App\Http\Controllers;

use Spatie\Tags\Tag;

class TagsController extends Controller
{
    public function __invoke()
    {
        return view(
            'tags.index',
            [
                'tags' => Tag::select('name')
                    ->cache(600)
                    ->get()
                    ->pluck('name')
                    ->sort()
            ]
        );
    }
}
