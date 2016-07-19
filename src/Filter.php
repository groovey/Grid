<?php

namespace Groovey\Grid;

use Pimple\Container;
use Symfony\Component\Yaml\Yaml;

class Filter extends Html
{
    public $app;
    public $yaml;

    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    public function setYaml($yaml)
    {
        $this->yaml = $yaml;
    }

    public function render($hidden = true)
    {
        $app     = $this->app;
        $yaml    = $this->yaml;
        $html    = '';
        $filters = coalesce($yaml['filters']);

        if (!$filters) {
            return '';
        }

        foreach ($filters as $filter) {
            $type       = element('type', $filter);
            $custom     = element('custom', $filter);
            $attributes = element('attributes', $filter);

            if ($custom) {
                $class    = element('class', $custom);
                $action   = element('action', $custom);
                $response = call_user_func_array([$class, $action], [$app]);

                $html .= coalesce($response['html']);
            } else {
                $html .= $this->$type($attributes);
            }
        }

        return $html.$this->renderHidden($hidden);
    }

    public function renderHidden($hidden)
    {
        $app = $this->app;

        if ($hidden) {
            $sort_field = $app['form']->hidden('sf', '');
            $sort_order = $app['form']->hidden('so', 'asc');
        } else {
            $sort_field = $app['form']->text('sf', '');
            $sort_order = $app['form']->text('so', 'asc');
        }

        return $sort_field.$sort_order;
    }
}
