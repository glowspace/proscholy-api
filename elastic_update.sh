#!/usr/bin/env bash

php artisan elastic:migrate:fresh &&
php artisan scout:import App\\SongLyric
php artisan scout:import App\\Author