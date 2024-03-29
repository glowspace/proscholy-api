<?php

namespace App\Providers;

use App\Services\ExternalMusicXmlService;
use App\Services\LilypondPartsService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

use App\Services\SongLyricModelService;
use App\Services\LilypondClientService;
use App\Services\RenderedScoreService;
use App\Services\SongLyricBibleReferenceService;
use App\Services\SongLyricLilypondService;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        if (config('url.redirect_https')) {
            URL::forceScheme('https');
            $this->app['request']->server->set('HTTPS', true);
        }

        Blade::directive('pushonce', function ($expression) {
            $domain = explode(':', trim(substr($expression, 1, -1)));
            $push_name = $domain[0];
            $push_sub = $domain[1];
            $isDisplayed = '__pushonce_' . $push_name . '_' . $push_sub;
            return "<?php if(!isset(\$__env->{$isDisplayed})): \$__env->{$isDisplayed} = true; \$__env->startPush('{$push_name}'); ?>";
        });

        Blade::directive('endpushonce', function ($expression) {
            return '<?php $__env->stopPush(); endif; ?>';
        });

        // uncomment for sql dumping
        // DB::listen(function($query) {
        //     logger($query->time);
        //     logger($query->sql, $query->bindings);
        // });

        Validator::extend('recaptcha', '\App\Validators\ReCaptcha@validate');

        try {
            Storage::extend('google', function ($app, $config) {
                logger($config['folder']);
                
                $options = [];

                if (!empty($config['teamDriveId'] ?? null)) {
                    $options['teamDriveId'] = $config['teamDriveId'];
                }

                $client = new \Google\Client();
                $client->setClientId($config['clientId']);
                $client->setClientSecret($config['clientSecret']);
                $client->refreshToken($config['refreshToken']);

                $service = new \Google\Service\Drive($client);
                $adapter = new \Masbug\Flysystem\GoogleDriveAdapter($service, $config['folder'] ?? '/', $options);
                $driver = new \League\Flysystem\Filesystem($adapter);

                return new \Illuminate\Filesystem\FilesystemAdapter($driver, $adapter);
            });
        } catch(\Exception $e) {
            // your exception handling logic
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(LilypondClientService::class, function () {
            return new LilypondClientService();
        });
        $this->app->singleton(RenderedScoreService::class, function () {
            return new RenderedScoreService();
        });
        $this->app->singleton(ExternalMusicXmlService::class, function () {
            return new ExternalMusicXmlService(
                app(LilypondClientService::class),
                app(RenderedScoreService::class)
            );
        });
        $this->app->singleton(SongLyricModelService::class, function () {
            return new SongLyricModelService(new SongLyricBibleReferenceService());
        });
        $this->app->singleton(RenderedScoreService::class, function () {
            return new RenderedScoreService();
        });
        $this->app->singleton(SongLyricLilypondService::class, function () {
            return new SongLyricLilypondService(
                app(LilypondClientService::class),
                app(LilypondPartsService::class),
                app(RenderedScoreService::class),
                app(SongLyricModelService::class)
            );
        });
    }
}
