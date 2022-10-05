# Notes

### To review
1. [Markdown to HTML](https://laravel-news.com/laravel-markdown-to-html-macro)
2. [HEIC to JPG](https://blog.genijaho.dev/how-to-add-support-for-heic-images-with-imagemagick-in-php)

### TODO
- [x] Add a new 'active' field to Items table and default to true.
- [ ] Add a Foreing-Id restiction to the og_item_id in the items table.
- [ ] Add a `duplicates()` relationship top the Item model
- [ ] Create a job to look for Items without media and try to update them. If it can't disable the Item.
- [ ] Install and configure [Horizon](https://laravel.com/docs/9.x/horizon).

### Snippets
1. Generate a temp signed url
```
$link = Illuminate\Support\Facades\URL::temporarySignedRoute(
    'preview', // route name
    now()->addMinutes(45), // TTL
    ['media' => $media->id] // object id
);
```
2. Respond to the temp url
```
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
```

### Not Implemented
- [x] <del>Create a job to scan all the imported files and add the exif data to the items table (EXIF for images, FFPROB for videos).</del>
- [x] <del>Create a job to scan for HEIC images, change the type from `video` to `image` and convert the file to JPG.</>
