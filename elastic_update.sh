#!/usr/bin/env bash

php artisan elastic:migrate:fresh --force &&
php artisan scout:import App\\SongLyric
php artisan scout:import App\\Author