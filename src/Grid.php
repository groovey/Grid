<?php

namespace Groovey\Grid;

use Pimple\Container;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Filesystem\Filesystem;

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
        $this->entry   = new Entry($app);
    }

    public function load($file, array $datas = [])
    {
        $app    = $this->app;
        $fs     = new Filesystem();
        $exists = $fs->exists($file);

        if (!$exists) {
            $app['trace']->show(true);
            $app['trace']->debug('File does not exist :' . $file);
            die();
        }

        $contents = $this->replace(file_get_contents($file), $datas);

        try {
            $yaml = Yaml::parse($contents);
        } catch (ParseException $e) {
            $app['trace']->show(true);
            $app['trace']->debug('Unable to parse the YAML string:');
            $app['trace']->debug($e);
            die();
        }

        $this->listing->setYaml($yaml);
        $this->filter->setYaml($yaml);
        $this->entry->setYaml($yaml);
    }

    public function replace($contents, $datas)
    {
        foreach ($datas as $key => $value) {
            $value    = Yaml::dump($value, 0);
            $contents = str_replace("{{ $key }}", $value, $contents);
        }

        return $contents;
    }
}
