<?php

namespace App\Http\Controllers\Admin;

use App\Author;
use App\Http\Controllers\Controller;
use App\Song;
use App\SongbookRecord;
use App\SongLyric;
use App\External;
use App\File;
use Illuminate\Http\Request;


class AdminController extends Controller
{
    public function renderDash()
    {
        return view('admin.dash', [
            'songs_count' => SongLyric::count(),

            'songs_w_text_count' => SongLyric::where('lyrics', '!=', '')->count(),
            'songs_w_chords_count' => SongLyric::where('has_chords', '=', true)->count(),
            'songs_w_score_count' => $this->countSongsWithScore(),
            'songs_w_lilypond_count' => $this->countSongsWithLilyPond(),
            'songs_w_license_count' => $this->countSongsWithLicense(),
            'songs_w_tags_count' => $this->countSongsWithTags(),

            'songs_w_all_count' => $this->countSongsWithAll(),
            'songs_w_just_title_count' => $this->countEmptySongs(),

            'authors_count' => Author::count(),
            'externals_count' => External::count(),
        ]);
    }

    private function countSongsWithAll(): int
    {
        return SongLyric::where('lyrics', '!=', '')
            ->where('has_chords', '=', true)
            ->where(function ($query) {
                $query->whereHas('authors', null)
                    ->orWhere('has_anonymous_author', 1);
            })->whereHas('tags')
            ->count();
    }

    private function countEmptySongs(): int
    {
        return SongLyric::whereNull('lyrics')
            ->where(function ($query) {
                $query->whereDoesntHave('authors', null)->where('has_anonymous_author', 0);
            })
            ->whereDoesntHave('tags', null)
            ->whereDoesntHave('songbook_records', null)
            ->whereDoesntHave('files', null)
            ->whereDoesntHave('externals')
            ->count();
    }

    /**
     * Count songs with any music score
     * (external/lilypond).
     *
     * @return mixed
     */
    private function countSongsWithScore(): int
    {
        return SongLyric::whereHas('externals', function ($q) { # Song has score in external
            # External must be score
            $q->where('content_type', '2');
        })
            ->orWhere('lilypond', '!=', null) # or song has lilypond score
            ->count();
    }

    /**
     * Count songs with filled LilyPond score.
     *
     * @return mixed
     */
    private function countSongsWithLilyPond(): int
    {
        return SongLyric::where('lilypond', '!=', null) # or song has lilypond score
        ->count();
    }

    /**
     * @return mixed
     */
    private function countSongsWithLicense(): int
    {
        return SongLyric::where('licence_type', '!=', null) # or song has lilypond score
        ->count();
    }

    private function countSongsWithTags()
    {
        return SongLyric::whereHas('tags')
            ->count();
    }
}
