## Laravel PHP Framework

[![Build Status](https://travis-ci.org/laravel/framework.svg)](https://travis-ci.org/laravel/framework)
[![License](http://opensource.org/licenses/MIT)

A package for storing key value data

## Config

Add ```\Opilo\KeyValue\Providers\KeyValueStoreProvider::class``` to your ```config/app.php```

Add ```php artisan vendor:publish --tag=public --force``` to ```post-install-cmd``` and ```post-update-cmd``` section of your ```composer.json``` scripts