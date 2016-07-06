<?php

namespace Groovey\Grid;

use Symfony\Component\Yaml\Yaml;

class Grid
{
    public $listing;
    public $entry;

    public function __construct($templatesPath, $cachePath)
    {
        $cache  = ($cachePath) ? ['cache' => $cachePath] : [];
        $loader = new \Twig_Loader_Filesystem($templatesPath);
        $twig   = new \Twig_Environment($loader, $cache);

        $this->listing = new Listing($twig);
    }

    public function load($config)
    {
        $yaml = Yaml::parse(file_get_contents($config));

        $this->listing->setYaml($yaml);
    }
}
