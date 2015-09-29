<?php namespace Opilo\KeyValue\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class KeyValueStoreProvider
 * @package Opilo\KeyValue\Providers
 */
class KeyValueStoreProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            dirname(__DIR__) . '/config/key-value-store.php' => config_path('key-value-store.php'),
        ], 'public');

        $this->publishes([
            dirname(__DIR__) . '/migration' => database_path('migrations'),
        ], 'public');
    }

    public function register()
    {
    }
}