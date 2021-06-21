<?php

namespace App\Services;

use Log;
use App\Author;
use App\SongLyric;
use App\Song;

use App\Jobs\UpdateSongLyricLilypond;
use App\SongLyricLilypondSrc;
use App\LilypondPartsSheetMusic;
use App\SongLyricLilypondSvg;
use App\SongLyricLyrics;

class SongLyricLilypondService
{
    protected LilypondClientService $ly_service;
    protected LilypondPartsService $lpsm_service;

    public function __construct(LilypondClientService $ly_service, LilypondPartsService $lpsm_service)
    {
        $this->ly_service = $ly_service;
        $this->lpsm_service = $lpsm_service;
    }

    private function handleLilypondSrc(SongLyric $song_lyric, $lilypond_src)
    {
        $wasEmpty = $song_lyric->lilypond_src === null;
        $isEmpty = $lilypond_src === null || $lilypond_src === '';

        if ($wasEmpty && !$isEmpty) {
            $lilypond_src_obj = new SongLyricLilypondSrc(['lilypond_src' => $lilypond_src]);
            $song_lyric->lilypond_src()->save($lilypond_src_obj);
        } else if (!$wasEmpty && !$isEmpty) {
            $song_lyric->lilypond_src()->update(['lilypond_src' => $lilypond_src]);
        } else if (!$wasEmpty && $isEmpty) {
            $song_lyric->lilypond_src()->delete();
        }

        $song_lyric->touch();
    }

    private function handleLilypondPartsSheetMusic(SongLyric $song_lyric, array $lilypond_parts_sheet_music)
    {
        // note: the input is validated by graphql types
        $lilypond_parts = $lilypond_parts_sheet_music['lilypond_parts'];
        $global_src = $lilypond_parts_sheet_music['global_src'];
        $sequence_string = $lilypond_parts_sheet_music['sequence_string'];
        $score_config = $lilypond_parts_sheet_music['score_config'];

        $wasEmpty = !$song_lyric->lilypond_parts_sheet_music()->exists();

        $lp_parts_sm = null;

        if ($wasEmpty) {
            $lp_parts_sm = new LilypondPartsSheetMusic([
                'lilypond_parts' => $lilypond_parts,
                'global_src' => $global_src,
                'sequence_string' => $sequence_string,
                'score_config' => $score_config,
            ]);
            $song_lyric->lilypond_parts_sheet_music()->save($lp_parts_sm);
        } else {
            $song_lyric->lilypond_parts_sheet_music()->update([
                'lilypond_parts' => $lilypond_parts,
                'global_src' => $global_src,
                'sequence_string' => $sequence_string,
                'score_config' => $score_config
            ]);
            $lp_parts_sm = $song_lyric->lilypond_parts_sheet_music->fresh();
        }

        // used later to filter out empty sheet music that do not produce a render result
        $song_lyric->lilypond_parts_sheet_music()->update([
            'is_empty' => empty(trim($lp_parts_sm->getPartsSrc()))
        ]);

        $song_lyric->touch();

        return $lp_parts_sm;
    }

    public function handleLilypondSvg(SongLyric $song_lyric, $lilypond_svg)
    {
        $wasEmpty = $song_lyric->lilypond_svg === null;
        $isEmpty = $lilypond_svg === null || $lilypond_svg === '';

        if ($wasEmpty && !$isEmpty) {
            $lilypond_svg_obj = new SongLyricLilypondSvg(['lilypond_svg' => $lilypond_svg]);
            $song_lyric->lilypond_svg()->save($lilypond_svg_obj);
        } else if (!$wasEmpty && !$isEmpty) {
            $song_lyric->lilypond_svg()->update(['lilypond_svg' => $lilypond_svg]);
        } else if (!$wasEmpty && $isEmpty) {
            $song_lyric->lilypond_svg()->delete();
        }

        $song_lyric->touch();
    }

    public function handleLilypond(SongLyric $song_lyric, $lilypond_input, $lilypond_key_major, array $lilypond_parts_sheet_music)
    {
        $old_lilypond_updated = (string)$song_lyric->lilypond_src != $lilypond_input || $lilypond_key_major != $song_lyric->lilypond_key_major;
        $new_lilypond_updated =
            $song_lyric->lilypond_parts_sheet_music->lilypond_parts != $lilypond_parts_sheet_music['lilypond_parts'] ||
            $song_lyric->lilypond_parts_sheet_music->score_config != $lilypond_parts_sheet_music['score_config'] ||
            $song_lyric->lilypond_parts_sheet_music->global_src != $lilypond_parts_sheet_music['global_src'] ||
            $song_lyric->lilypond_parts_sheet_music->sequence_string != $lilypond_parts_sheet_music['sequence_string'];

        logger("Old lilypond updated: $old_lilypond_updated");
        logger("New lilypond updated: $new_lilypond_updated");

        $this->handleLilypondSrc($song_lyric, $lilypond_input);
        $lp_parts_sm = $this->handleLilypondPartsSheetMusic($song_lyric, $lilypond_parts_sheet_music);

        // this task then calls handleLilypondSvg in this class
        // todo: remove in favour of serving svg files
        UpdateSongLyricLilypond::dispatchIf(
            $old_lilypond_updated || $new_lilypond_updated,
            $song_lyric->id
        );

        // new way of rendering
        // needs to be allowed in .env with USE_RENDERED_SCORES=true
        if ($new_lilypond_updated && config('lilypond.use_rendered_scores')) {
            logger("Song lyric LP Parts Sheet music updated, doing the render");

            $this->lpsm_service->renderLilypondScoresSheetMusic($lp_parts_sm);
        }

        // with that, the lilypond is updated
    }
}
