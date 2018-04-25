<?php

namespace Origami\Consent;

use Illuminate\Support\ServiceProvider;

class ConsentServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/consent.php' => config_path('consent.php'),
        ], 'config');

        if (!class_exists('CreateConsentTable')) {
            $timestamp = date('Y_m_d_His', time());

            $this->publishes([
                __DIR__ . '/../database/migrations/create_consent_table.php.stub' => $this->app->databasePath() . "/migrations/{$timestamp}_create_consent_table.php",
            ], 'migrations');
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/consent.php',
            'consent'
        );
    }
}
