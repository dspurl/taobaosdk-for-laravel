<?php

namespace Dspurl\Taobaosdk;

use Illuminate\Support\ServiceProvider;

class TaobaosdkServiceProvider extends ServiceProvider
{
    /**
     * 服务提供者加是否延迟加载.
     *
     * @var bool
     */
    protected $defer = true; // 延迟加载服务
    
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('taobaosdk', function ($app) {
            return new Taobaosdk($app['session'], $app['config']);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/views', 'Taobaosdk'); // 视图目录指定
        $this->publishes([
            __DIR__.'/config/taobaosdk.php' => config_path('taobaosdk.php'), // 发布配置文件到 laravel 的config 下
        ]);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        // 因为延迟加载 所以要定义 provides 函数 具体参考laravel 文档
        return ['taobaosdk'];
    }
}
