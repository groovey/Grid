<?php

namespace Groovey\Grid;

use Pimple\Container;

class Custom
{
    public static function header(Container $app)
    {
        return '<font color=red>Custom Header</font>';
    }
    public static function body(Container $app, $data)
    {
        $name = $data['name'];

        return "<font color=blue>Custom Content | name = {$name}</font>";
    }

    public static function filter(Container $app)
    {
        $html      = '<font color=red>Custom Filter</font>';
        $value     = $app['request']->get('filter_custom');

        $operation = '';

        if ($value) {
            $operation = "(name LIKE '%$value%' OR name LIKE '%$value')";
        }

        return ['html' => $html, 'operation' => $operation];
    }

    public static function entry(Container $app, $param)
    {
        $label = $param['label'];
        $help  = $param['help'];
        $form  = '<input type="text" style="background-color:red" value="This is a custom message">';

        $html  = $app['twig']->render('grid/entry/container.html', [
                                'form'  => $form,
                                'label' => $label,
                                'help'  => $help,
                            ]);

        return $html;
    }
}
