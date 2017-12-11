<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * 在容器中注册绑定.
     *
     * @return void
     * @author http://laravelacademy.org
     */
    public function boot()
    {
        view()->composer(
            '*',
            'App\Http\ViewComposers\FavoritesComposer'
        );
        // ...
    }
    /**
     * 注册服务提供者.
     *
     * @return void
     */
    public function register()
    {
        # code...
    }
}