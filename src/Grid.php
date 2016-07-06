<?php

namespace Groovey\Grid;

use Symfony\Component\Yaml\Yaml;
use Groovey\ORM\DB;

class Grid
{

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

        $datas = [];
        foreach ($yaml['listing'] as $value) {

            extract($value['header']);

            $datas[] = [
                'label' => coalesce($label),
                'width' => coalesce($width),
            ];
        }

        return $twig->render('listing/header.html', [
                                'datas' => $datas
                            ]);
    }

    public function renderListingBody()
    {
        $twig    = $this->twig;
        $yaml    = $this->yaml;
        $results = DB::select($yaml['sql']);

        $datas = [];
        foreach ($results as $result) {

            $result = (array) $result;

            $cnt = 0;
            foreach ($yaml['listing'] as $value) {

                extract($value['body']);

                $temp[$cnt++] = coalesce($result[$row]);
            }

            $datas[] = $temp;
        }

        return $twig->render('listing/body.html', [
                                        'datas' => $datas,
                                    ]);

    }

    public function render($type = '')
    {
        $html = $this->html;

        switch (strtolower($type)) {
            case 'listing-header':
                return $this->renderListingHeader();
                break;

            case 'listing-data':
                return $this->renderListingBody();
                break;
            default:
                break;
        }
    }

}