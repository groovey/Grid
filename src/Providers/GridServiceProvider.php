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
            return new Grid($app);
        };
    }

    public function boot(Application $app)
    {
    }
}
