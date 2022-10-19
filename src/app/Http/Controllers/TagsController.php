<?php

namespace App\Http\Controllers;

use App\Traits\TagsCacheable;

class TagsController extends Controller
{
    use TagsCacheable;

    public function __invoke()
    {
        return view(
            'tags.index',
            ['tags' => $this->getAllTags()]
        );
    }
}
