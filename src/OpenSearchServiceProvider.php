<?php

namespace Sav\OpenSearch;

use Illuminate\Support\ServiceProvider;

class OpenSearchServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     * @return void
     */
    public function register()
    {
        $this->app->bind('open-search-service', function () {
            return new OpenSearch();
        });
    }

}
