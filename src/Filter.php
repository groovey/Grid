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

    public function render()
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
                $class  = element('class', $custom);
                $action = element('action', $custom);

                $html .= call_user_func(
                                [__NAMESPACE__.'\\'.$class, $action],
                                []
                            );
            } else {
                $html .= $this->$type($attributes);
            }
        }

        return $html;
    }
}
