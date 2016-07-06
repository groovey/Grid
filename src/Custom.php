<?php

namespace Groovey\Grid;

class Custom
{
    public static function header()
    {
        return '<font color=red>Custom Header</font>';
    }
    public function body()
    {
        return '<font color=red>Custom Content | id = </font>';
    }
}
