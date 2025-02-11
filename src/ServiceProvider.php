<?php

namespace Edge\PowerbiEmbed;
use Illuminate\Support\Facades\Blade;

use Statamic\Providers\AddonServiceProvider;
use Edge\PowerBiEmbed\View\Components\PowerBiEmbed;

class ServiceProvider extends AddonServiceProvider
{
    protected $tags = [
        Tags\PowerBiEmbed::class,
    ];

    public function bootAddon()
    {
        // Register the view path
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'powerbi-embed');
    }

    public function boot()
    {
        parent::boot();

        Blade::component('powerbi-embed', PowerBiEmbed::class);

        $this->publishes([
            __DIR__.'/../config/powerbi-embed.php' => config_path('powerbi-embed.php'),
        ], 'powerbi-embed-config');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/powerbi-embed'),
        ], 'powerbi-embed-views');
    }
}
