<?php

namespace Groovey\Grid;

use Pimple\Container;
use Symfony\Component\Yaml\Yaml;

class Listing extends QueryBuilder
{
    public $app;

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
                $label  = call_user_func([$class, $action], $app);
            }

            $datas[] = [
                'label' => $label,
                'width' => $width,
            ];
        }

        return $app['twig']->render('grid/listing/header.html', [
                                'datas' => $datas,
                            ]);
    }

    public function body()
    {
        $app     = $this->app;
        $yaml    = $this->yaml;
        $records = $this->getRecords();

        $datas = [];
        foreach ($records as $record) {
            $record = (array) $record;
            $cnt    = 0;

            foreach ($yaml['listing'] as $value) {
                $body    = $value['body'];
                $custom  = element('custom', $body);
                $row     = element('row', $body);
                $align   = element('align', $body);
                $actions = element('actions', $body);
                $label   = coalesce($record[$row]);

                if ($custom) {
                    $class  = element('class', $custom);
                    $action = element('action', $custom);
                    $label  = call_user_func_array([$class, $action], [$app, $record]);
                } elseif ($actions) {
                    $label = $this->renderActions($actions);
                }

                $temp[$cnt++] = [
                            'row'   => $label,
                            'align' => $align,
                        ];
            }

            $datas[] = $temp;
        }

        return $app['twig']->render('grid/listing/body.html', [
                                        'datas' => $datas,
                                    ]);
    }

    public function renderActions($actions)
    {
        $app = $this->app;

        $delete = element('delete', $actions, false);
        $edit   = element('edit', $actions, false);

        $html = $app['twig']->render('grid/listing/actions.html', [
                                'delete' => $delete,
                                'edit'   => $edit,
                            ]);

        return $html;
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
