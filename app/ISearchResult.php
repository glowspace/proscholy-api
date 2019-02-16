<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;

interface ISearchResult
{
    public function getSearchTitle();
    public function getSearchText();
}