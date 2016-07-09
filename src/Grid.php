<?php

namespace Groovey\Grid;

use Pimple\Container;
use Symfony\Component\Yaml\Yaml;

class Grid
{
    public $app;
    public $listing;
    public $entry;
    public $filter;
    public $paging;

    public function __construct(Container $app)
    {
        $this->app     = $app;
        $this->paging  = $app['paging'];
        $this->listing = new Listing($app);
        $this->filter  = new Filter($app);
    }

    public function load($config)
    {
        $yaml = Yaml::parse(file_get_contents($config));

        $this->listing->setYaml($yaml);
        $this->filter->setYaml($yaml);
    }
}
