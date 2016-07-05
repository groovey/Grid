<?php

namespace Groovey\Grid;

use Symfony\Component\Yaml\Yaml;

class Grid {

    public function __construct()
    {

    }

    public function load($config)
    {
        $yaml = Yaml::parse(file_get_contents($config));

        print_r( $yaml);

    }

}