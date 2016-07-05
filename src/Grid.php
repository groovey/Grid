<?php

namespace Groovey\Grid;

use Symfony\Component\Yaml\Yaml;

class Grid {

    private $twig;
    private $yaml;
    private $html;

    public function __construct($templatesPath, $cachePath)
    {
        $cache  = ($cachePath) ? ['cache' => $cachePath] : [];
        $loader = new \Twig_Loader_Filesystem($templatesPath);
        $twig   = new \Twig_Environment($loader, $cache);

        $this->twig = $twig;
    }

    public function load($config)
    {
        $this->yaml = Yaml::parse(file_get_contents($config));
    }

    public function renderListingHeader()
    {
        $twig = $this->twig;
        $yaml = $this->yaml;

        $html = '';
        foreach ($yaml['listing'] as $value) {
            $header = $value['header'];

            extract($header);

            $html .= $twig->render('listing/header.html', [
                        'label'   => coalesce($label),
                        'width'   => coalesce($label),
                    ]);
        }
        return $html;
    }

    public function renderListingData($datas)
    {
        $twig = $this->twig;
        $yaml = $this->yaml;

        $html = '';
        // foreach ($yaml['data'] as $value) {
        //     $data = $value['data'];

        //     extract($data);

        //     $html .= $twig->render('listing/data.html', [
        //                     'value' => coalesce($label),
        //                 ]);
        // }
        return $html;

    }

    public function render()
    {

    }

}