# Notes

### To review
1. [Markdown to HTML](https://laravel-news.com/laravel-markdown-to-html-macro)

### TODO
- [ ] Dial down the item import to once every 3 hours.

### Completed
- [x] Add a new 'active' field to Items table and default to true.
- [x] Install and configure [Horizon](https://laravel.com/docs/9.x/horizon).
- [x] [HEIC to JPG](https://blog.genijaho.dev/how-to-add-support-for-heic-images-with-imagemagick-in-php)
- [x] Add a `source` field to Posts table to see where did the content comes from


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
3. Get extended Exif data
```
    private function getExif(string $file): array
    {
        $fileInfo = new SplFileInfo($file);
        $baseData = [
            'full_path' => $fileInfo->getRealPath() ?? $file,
            'path' => $fileInfo->getPath(),
            'file_name' => $fileInfo->getFilename(),
            'extension' => $fileInfo->getExtension(),
            'size' => $fileInfo->getSize(),
            'modified_at' => $fileInfo->getMTime(),
        ];

        try {
            $data = exif_read_data($file);
            if (!empty($data)) {
                return array_merge($baseData, $data);
            }
        } catch (Exception) {   }

        try {
            $prober = FFProbe::create();
            $data = $prober->format($file)->all();
            return array_merge($baseData, $data);
        } catch (Exception) {
            return $baseData;
        }
    }
```

### Queries
```
update `cnt_bibles` set used = 0;
update `cnt_birthdays` set used = 0;
update `cnt_history` set used = 0;
update `cnt_words` set used = 0;
update `cnt_jokes` set used = 0;
update `cnt_quotes` set used = 0;
update `cnt_quran` set used = 0;
```

### Not Implemented
- [x] <del>Create a job to scan all the imported files and add the exif data to the items table (EXIF for images, FFPROB for videos).</del>
- [x] <del>Create a job to scan for HEIC images, change the type from `video` to `image` and convert the file to JPG.</>
- [x] <del>Add a Foreign-Id restriction to the og_item_id in the items table.</dev>
- [x] <del>Add a `duplicates()` relationship top the Item model.<del>
- [x] <del>Create a job to look for Items without media and try to update them. If it can't disable the Item.</del>
