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
        $app       = $this->app;
        $html      = '';
        $action    = $app['request']->get('action', 'listing');
        $page      = $app['request']->get('page', 1);
        $sortField = $app['request']->get('sort_field', '');
        $sortOrder = $app['request']->get('sort_order', 'asc');

        if ($hidden) {
            $html .= $app['form']->hidden('action', $action);
            $html .= $app['form']->hidden('sort_field', $sortField);
            $html .= $app['form']->hidden('sort_order', $sortOrder);
            $html .= $app['form']->hidden('page', $page);
        } else {
            $html .= $app['form']->text('action', $action);
            $html .= $app['form']->text('sort_field', $sortField);
            $html .= $app['form']->text('sort_order', $sortOrder);
            $html .= $app['form']->text('page', $page);
        }

        return $html;
    }
}
