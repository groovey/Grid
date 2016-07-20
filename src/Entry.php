<?php

namespace Groovey\Grid;

use Pimple\Container;
use Symfony\Component\Yaml\Yaml;

class Entry extends FormBuilder
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

    public function render()
    {
        $app     = $this->app;
        $yaml    = $this->yaml;

        $datas = [];
        foreach ($yaml['entry'] as $value) {
            $type       = element('type', $value);
            $label      = element('label', $value);
            $required   = element('required', $value);
            $help       = element('help', $value);
            $attributes = element('attributes', $value);

            $misc = [
                    'type'     => $type,
                    'label'    => $label,
                    'required' => $required,
                    'help'     => $help,
                ];

            $form = $this->input($app, $misc, $attributes);

            $datas[] = $form;

            // $datas[] = [
            //     'type'     => $type,
            //     'label'    => $label,
            //     'required' => $required,
            //     'form'     => $form
            // ];
        }
        dump($datas);

        return $app['twig']->render('grid/entry/entry.html', [
                                'datas' => $datas,
                            ]);
    }
}
