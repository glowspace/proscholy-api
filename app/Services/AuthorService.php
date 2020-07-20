<?php

namespace App\Services;

use App\Author;
use App\SongLyric;

class AuthorService
{
    public function song_lyrics_interpreted(Author $author)
    {
        return SongLyric::whereHas('externals', function ($q) use ($author) {
            $q->media()->whereHas('authors', function ($a) use ($author) {
                $a->where('authors.id', $author->id);
            });
        })->orWhereHas('files', function ($q) use ($author) {
            $q->audio()->whereHas('authors', function ($a) use ($author) {
                $a->where('authors.id', $author->id);
            });
        });
    }

    public function getAssociatedAuthorsIds(Author $author)
    {
        $authors = collect([$author]);

        return $authors->merge($author->members()->get())->map(function ($a) {
            return $a["id"];
        })->toArray();
    }

    public function song_lyrics_include_associated_authors(Author $author)
    {
        $ids = $this->getAssociatedAuthorsIds($author);

        return SongLyric::whereHas('authors', function ($q) use ($ids) {
            $q->whereIn('authors.id', $ids);
        });
    }
}
