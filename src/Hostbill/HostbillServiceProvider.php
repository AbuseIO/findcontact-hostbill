<?php

namespace AbuseIO\Findcontact\;

use Illuminate\Support\ServiceProvider;

class HostbillServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     * merge the config
     *
     * @return void
     */
    public function boot()
    {
        // Publish config
        $this->mergeConfigFrom(base_path('vendor/abuseio/findcontact-hostbill').'/config/Hostbill.php', 'Findcontact');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}