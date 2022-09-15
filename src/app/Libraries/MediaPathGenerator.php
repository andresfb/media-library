<?php

namespace App\Libraries;

use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class MediaPathGenerator implements PathGenerator
{
    /**
     * getPath Method.
     *
     * @param Media $media
     * @return string
     */
    public function getPath(Media $media): string
    {
        return $this->getBasePath($media).'/';
    }

    /**
     * getPathForConversions Method.
     *
     * @param Media $media
     * @return string
     */
    public function getPathForConversions(Media $media): string
    {
        return $this->getBasePath($media).'/conversions/';
    }

    /**
     * getPathForResponsiveImages Method.
     *
     * @param Media $media
     * @return string
     */
    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getBasePath($media).'/responsive-images/';
    }

    /**
     * getBasePath Method.
     *
     * @param Media $media
     * @return string
     */
    private function getBasePath(Media $media): string
    {
        $contentId = str_pad(strval($media->model_id), 12, "0", STR_PAD_LEFT);
        return Str::of(
            collect(str_split($contentId, 3))
                ->reverse()
                ->implode("/")
        )
        ->append("/")
        ->append($media->collection_name)
        ->append("/")
        ->append(strval($media->id))
        ->__toString();
    }
}
