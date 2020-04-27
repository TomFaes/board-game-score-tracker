<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $socialite = $this->app->make('Laravel\Socialite\Contracts\Factory');
        $socialite->extend(
            'microsoft',
            function ($app) use ($socialite) {
                $config = $app['config']['services.microsoft'];
                return $socialite->buildProvider(\App\Services\SocialiteProviders\MicrosoftProvider::class, $config);
            }
        );
        $socialite->extend(
            'facebook',
            function ($app) use ($socialite) {
                $config = $app['config']['services.facebook'];
                return $socialite->buildProvider(\App\Services\SocialiteProviders\FacebookProvider::class, $config);
            }
        );

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('path.public', function()
        {
            return base_path('public_html');
        });
        
        if ($this->app->environment() !== 'production') {
            $this->app->register(IdeHelperServiceProvider::class);
        }
        Schema::defaultStringLength(191);

        $this->app->bind('App\Repositories\Contracts\IUser', 'App\Repositories\UserRepo');

        $this->app->bind('App\Repositories\Contracts\IGame', 'App\Repositories\GameRepo');

        $this->app->bind('App\Repositories\Contracts\IGroup', 'App\Repositories\GroupRepo');
        $this->app->bind('App\Repositories\Contracts\IGroupUser', 'App\Repositories\GroupUserRepo');
        $this->app->bind('App\Repositories\Contracts\IGroupGame', 'App\Repositories\GroupGameRepo');
        $this->app->bind('App\Repositories\Contracts\IGroupGameLink', 'App\Repositories\GroupGameLinkRepo');
        $this->app->bind('App\Repositories\Contracts\IPlayedGame', 'App\Repositories\PlayedGameRepo');
        $this->app->bind('App\Repositories\Contracts\IPlayedGameScore', 'App\Repositories\PlayedGameScoreRepo');

    }
}
