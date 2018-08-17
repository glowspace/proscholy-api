<?php

namespace App\Http\Controllers;

use App\SongTranslation;

class TranslationController extends Controller
{
    public function renderTranslation($id)
    {
        $translation         = SongTranslation::findOrFail($id);
        $translation->visits = $translation->visits + 1;
        $translation->save();

        return view('translation', [
            'translation' => $translation,
            'page_title'  => $translation->name,
        ]);
    }


}
