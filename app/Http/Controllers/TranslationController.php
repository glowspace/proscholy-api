<?php

namespace App\Http\Controllers;

use App\SongLyric;

class TranslationController extends Controller
{
    public function renderTranslation($id)
    {
        $translation         = SongLyric::findOrFail($id);
        $translation->visits = $translation->visits + 1;
        $translation->save();

        return view('translation', [
            'translation' => $translation,
            'page_title'  => $translation->name,
        ]);
    }


}
