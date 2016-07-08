<?php

namespace Groovey\Grid;

class Html
{
    public function __construct()
    {
    }

    public function select($attributes)
    {
        $app      = $this->app;
        $name     = element('name', $attributes);
        $options  = element('options', $attributes);
        $selected = element('selected', $attributes);

        unset($attributes['name']);
        unset($attributes['options']);
        unset($attributes['selected']);

        return $app['form']->select($name, $options, $selected, $attributes);
    }
}
