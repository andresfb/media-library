# Notes

### TODO
- [ ] Wire the `comments, info, and settings` buttons in the content page.
- [ ] Wire the `loader <div>` to a livewire component and load the next page of posts.
- [ ] Add the ability to assign new tags.
    
### Completed
- [x] Add a new 'active' field to Items table and default to true.
- [x] Install and configure [Horizon](https://laravel.com/docs/9.x/horizon).
- [x] [HEIC to JPG](https://blog.genijaho.dev/how-to-add-support-for-heic-images-with-imagemagick-in-php)
- [x] Add a `source` field to Posts table to see where did the content comes from
- [x] Dial down the item import to once every 3 hours.
- [x] Move getting the items resolution to a Job to scan the files and save to database (media table as custom properties).
- [x] Display the `videos` in the content page.
- [x] Add a new Comments table and relate it to Post.

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
- [x] <del>Change the Extra Info to load on-demand.</del>
