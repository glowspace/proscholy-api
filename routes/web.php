<?php

/**
 *--------------------------------------------------------------------------
 * ProScholy.cz Main web routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register web routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * contains the "web" middleware group. Now create something great.
 *
 * Glory be to the Father, and to the Son, and to the Holy Spirit.
 * As it was in the beginning, is now, and ever shall be, world without end.
 * Amen
 *
 * Routes are nested in separate files, grouped by their purpose.
 * Order of imports is important.
 */

// Load administration routes
require 'web_admin.php';

// Register public web routes (with SPA)
require 'web_public.php';
