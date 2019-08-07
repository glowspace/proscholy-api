#!/usr/bin/env bash

NEW_UUID=$(cat /dev/urandom | tr -dc 'a-z0-9' | fold -w 4 | head -n 1)

php artisan elastic:migrate "App\SongLyric" song_lyric_${NEW_UUID}
php artisan elastic:migrate "App\Author" author_${NEW_UUID}