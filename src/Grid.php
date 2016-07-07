<?php

namespace Groovey\Grid;

use Pimple\Container;
use Symfony\Component\Yaml\Yaml;

class Grid
{
    public $listing;
    public $entry;
    public $app;

    public function __construct(Container $app)
    {
        $this->app = $app;
        $this->listing = new Listing($app);
    }

    public function load($config)
    {
        $yaml = Yaml::parse(file_get_contents($config));

        $this->listing->setYaml($yaml);
    }
}
