<?php

namespace Groovey\Grid;

use Pimple\Container;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

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

    public function load($file)
    {
        $app = $this->app;

        try {
            $yaml = Yaml::parse(file_get_contents($file));
        } catch (ParseException $e) {
            // $app->debug("Unable to parse the YAML string: %s");
        }

        $this->listing->setYaml($yaml);
        $this->filter->setYaml($yaml);
    }
}
