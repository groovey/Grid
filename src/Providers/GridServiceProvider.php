<?php

namespace Groovey\Grid\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Application;
use Silex\Api\BootableProviderInterface;
use Groovey\Grid\Grid;

class GridServiceProvider implements ServiceProviderInterface, BootableProviderInterface
{
    public function register(Container $app)
    {
        $app['grid'] = function ($app) {

            $templates = $app['grid.templates'];
            $cache     = (isset($app['menu.cache'])) ? $app['menu.cache'] : '';

            return new Grid($templates, $cache);
        };
    }

    public function boot(Application $app)
    {
    }
}
