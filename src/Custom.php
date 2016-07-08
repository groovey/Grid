<?php

namespace Groovey\Grid;

class Custom
{
    public static function header()
    {
        return '<font color=red>Custom Header</font>';
    }
    public static function body($data)
    {
        $name = $data['name'];

        return "<font color=blue>Custom Content | name = {$name}</font>";
    }

    public static function filter()
    {
        return '<font color=red>Custom Filter</font>';
    }
}
