<?php

namespace Groovey\Grid;

use Pimple\Container;
use Symfony\Component\Yaml\Yaml;
use Groovey\ORM\DB;

class Listing
{
    private $app;
    private $yaml;

    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    public function setYaml($yaml)
    {
        $this->yaml = $yaml;
    }

    public function header()
    {
        $app  = $this->app;
        $yaml = $this->yaml;

        $datas = [];
        foreach ($yaml['listing'] as $value) {
            $header = $value['header'];

            $custom = element('custom', $header);
            $label  = element('label', $header);
            $width  = element('width', $header);

            if ($custom) {
                $class  = element('class', $custom);
                $action = element('action', $custom);
                $label  = call_user_func(
                                [__NAMESPACE__.'\\'.$class, $action],
                                []
                            );
            }

            $datas[] = [
                'label' => $label,
                'width' => $width,
            ];
        }

        return $app['twig']->render('listing/header.html', [
                                'datas' => $datas,
                            ]);
    }

    public function body()
    {
        $app     = $this->app;
        $yaml    = $this->yaml;
        $results = DB::select($yaml['sql']);

        $datas = [];
        foreach ($results as $result) {
            $result = (array) $result;

            $cnt = 0;
            foreach ($yaml['listing'] as $value) {
                $body    = $value['body'];
                $custom  = element('custom', $body);
                $row     = element('row', $body);
                $align   = element('align', $body);
                $actions = element('actions', $body);

                $label  = coalesce($result[$row]);

                if ($custom) {
                    $class  = element('class', $custom);
                    $action = element('action', $custom);
                    $label  = call_user_func(
                                    [__NAMESPACE__.'\\'.$class, $action],
                                    $result
                                );
                } elseif ($actions) {
                    $label = $this->createActions($actions);
                }

                $temp[$cnt++] = [
                            'row'   => $label,
                            'align' => $align,
                        ];
            }

            $datas[] = $temp;
        }

        return $app['twig']->render('listing/body.html', [
                                        'datas' => $datas,
                                    ]);
    }

    public function createActions($actions)
    {
        return 'todo';
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
