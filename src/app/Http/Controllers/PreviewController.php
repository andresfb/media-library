<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PreviewController extends Controller
{
    /**
     * __invoke Method.
     *
     * @param Request $request
     * @param Media $media
     * @return BinaryFileResponse
     */
    public function __invoke(Request $request, Media $media)
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }

        return response()->file(
            $media->getPath(),
            ['Content-type' => $media->mime_type]
        );
    }
}
