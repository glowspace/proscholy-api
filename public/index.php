<?php

/**
 * Zpěvník ProScholy.cz
 *
 * @package Zpěvník ProScholy.cz
 */

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels great to relax.
|
| Our Father who art in heaven, hallowed be Thy Name. Thy Kingdom come.
| Thy Will be done, on earth as it is in heaven. Give us this day
| our daily bread. And forgive us our trespasses,  as we forgive those
| who trespass against us. And lead us not into temptation,
| but deliver us from evil.
|
| Amen
*/

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let us turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight our users.
|
| Gen 1, 1-5
|
| In the beginning, God created the heavens and the earth.
| The earth was without form and void, and darkness was over the face of the deep.
| And the Spirit of God was hovering over the face of the waters.
|
| And God said, “Let there be light,” and there was light.
| And God saw that the light was good. And God separated the light from the darkness.
| God called the light Day, and the darkness he called Night.
| And there was evening and there was morning, the first day.
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
