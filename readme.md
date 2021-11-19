# Laravel AutoDoc plugin 

This package is used as a driver for the 'laravel-swagger' package.

## Instalation

### Composer
 1. Add to required `"ronasit/laravel-remote-data-collector": "master"`
 1. Run `composer update`

### Laravel
 1. Add **RemoteDataCollectorServiceProvider::class** to providers in *config/app.php*
 1. Add **RemoteDataCollector::class as value to key 'data_collector'** in *config/auto-doc.php*
 1. Run `php artisan vendor:publish`
 
### ENV
 In *.env* file you should add following lines
    `
    DATA_COLLECTOR_KEY=some_project_name
    `

### config - remote-data-collector
    `
    url=/example-url
    `

## License

Auto-doc plugin is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
