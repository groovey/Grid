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
            $custom     = element('custom', $value);
            $type       = element('type', $value);
            $label      = element('label', $value);
            $required   = element('required', $value);
            $help       = element('help', $value);
            $text       = element('text', $value);
            $attributes = element('attributes', $value);
            $permission = element('permission', $value);

            if ($permission && !$app['acl']->allow($permission)) {
                continue;
            }

            $misc = [
                    'type'     => $type,
                    'label'    => $label,
                    'required' => $required,
                    'text'     => $text,
                    'help'     => $help,
                ];

            if ($custom) {
                $class  = element('class', $custom);
                $action = element('action', $custom);
                $param  = ['label' => $label, 'help' => $help];
                $form   = call_user_func([$class, $action], $app, $param);
            } else {
                $form = $this->build($app, $misc, $attributes);
            }

            $datas[] = $form;
        }

        return $app['twig']->render('grid/entry/entry.html', [
                                'datas' => $datas,
                            ]);
    }
}
