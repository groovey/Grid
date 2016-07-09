<?php

namespace Groovey\Grid;

use Symfony\Component\Yaml\Yaml;
use Groovey\ORM\DB;

class Query
{
    public $app;
    public $yaml;
    public $sql;

    public $total;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function setYaml($yaml)
    {
        $this->yaml = $yaml;

        $sql = $yaml['query'];

        $this->sql = [
            'search' => element('search', $sql),
            'total'  => element('total', $sql),
            'select' => element('select', $sql),
            'from'   => element('from', $sql),
            'where'  => element('where', $sql),
            'group'  => element('group', $sql),
            'order'  => element('order', $sql),
            'limit'  => element('limit', $sql),
        ];
    }

    public function composeSearch()
    {
    }

    public function composeWhere()
    {
        extract($this->sql);

        // TODO

        return $statement = $where;
    }

    public function composeGroup()
    {
        return '';
    }

    public function total()
    {
        return $this->total;
    }

    public function getTotalRecords()
    {
        extract($this->sql);

        $where = $this->composeWhere();

        $sql = "SELECT $total AS total FROM $from WHERE $where LIMIT 1 ";
        echo "<Br/> total = $sql";

        $result = DB::select($sql);

        $this->total = $result[0]->total;

        return $this->total;
    }

    public function results()
    {
        $app          = $this->app;
        $sql          = $this->sql;
        $select       = element('select', $sql);
        $from         = element('from', $sql);
        $limit        = element('limit', $sql);

        $totalRecords = $this->getTotalRecords();
        $where        = $this->composeWhere();

        $app['paging']->limit($limit);
        $app['paging']->process(1,  $totalRecords);

        $offset = $app['paging']->offset();
        $limit  = $app['paging']->limit();

        $sql = "SELECT $select FROM $from WHERE $where LIMIT $offset, $limit";
        echo "<Br/> sql = $sql";

        $results = DB::select($sql);

        return $results;
    }
}
