<?php

namespace Groovey\Grid;

use Symfony\Component\Yaml\Yaml;
use Groovey\ORM\DB;

class Listing
{
    public $twig;
    public $yaml;

    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    public function setYaml($yaml)
    {
        $this->yaml = $yaml;
    }

    public function header()
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
                                'datas' => $datas,
                            ]);
    }

    public function body()
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

                $temp[$cnt++] = [
                            'row'   => coalesce($result[$row]),
                            'align' => coalesce($align),
                        ];
            }

            $datas[] = $temp;
        }

        return $twig->render('listing/body.html', [
                                        'datas' => $datas,
                                    ]);
    }

    public function render($type)
    {
        if ('header' == $type) {
            return $this->header();
        } elseif ('body' == $type) {
            return $this->body();
        }
    }
}
