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

    public function renderHidden($hidden = true)
    {
        $app = $this->app;

        $html = '';
        $page = $app['request']->get('p', 1);
        $sortField = $app['request']->get('sf', '');
        $sortOrder = $app['request']->get('so', 'asc');

        if ($hidden) {
            $html .= $app['form']->hidden('sf', $sortField);
            $html .= $app['form']->hidden('so', $sortOrder);
            $html .= $app['form']->hidden('p', $page);
        } else {
            $html .= $app['form']->text('sf', $sortField);
            $html .= $app['form']->text('so', $sortOrder);
            $html .= $app['form']->text('p', $page);
        }

        return $html;
    }
}
