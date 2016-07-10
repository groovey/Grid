<?php

namespace Groovey\Grid;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Yaml;
use Groovey\ORM\DB;

class QueryBuilder
{
    public $yaml;
    public $total;

    public function getSql()
    {
        $sql = $this->yaml['sql'];

        return [
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
        $app    = $this->app;
        $sql    = $this->getSql();
        $search = element('search', $sql);
        $q      = $app['request']->get('q');

        if (!$q || !$search) {
            return;
        }

        $temp = [];
        foreach ($search as $value) {
            $temp[] = " $value LIKE '%$q%' ";
        }

        return implode(' AND ', $temp);
    }

    public function composeWhere()
    {
        $sql    = $this->getSql();
        $where  = element('where', $sql, '1');
        $search = $this->composeSearch();

        $query = $temp[] = $where;
        if ($search) {
            $temp[] = $search;
            $query  = implode(' AND', $temp);
        }

        return $query;
    }

    public function composeGroup()
    {
        $sql   = $this->getSql();
        $group = element('group', $sql);

        if (!$group) {
            return;
        }

        return "GROUP BY $group";
    }

    public function composeOrder()
    {
        $app       = $this->app;
        $sql       = $this->getSql();
        $order     = element('order', $sql, '');
        $sortField = $app['request']->get('sort_field');
        $sortOrder = $app['request']->get('sort_order');

        $query = ($order) ? "ORDER BY $order" : '';

        if ($sortField && $sortOrder) {
            $query = "ORDER BY $sortField $sortOrder";
        }

        return $query;
    }

    public function total()
    {
        return $this->total;
    }

    public function getTotalRecords()
    {
        $app   = $this->app;
        $sql   = $this->getSql();
        $total = element('total', $sql);
        $from  = element('from', $sql);
        $where = $this->composeWhere();
        $group = $this->composeGroup();

        $query = "SELECT $total AS total FROM $from WHERE $where $group LIMIT 1 ";

        $app->debug('getTotalRecords = '.$query);

        $result = DB::select($query);

        return $this->total = $result[0]->total;
    }

    public function getRecords()
    {
        $app          = $this->app;
        $sql          = $this->getSql();
        $totalRecords = (int) $this->getTotalRecords();
        $select       = element('select', $sql);
        $from         = element('from', $sql);
        $limit        = element('limit', $sql);
        $where        = $this->composeWhere();
        $group        = $this->composeGroup();
        $order        = $this->composeOrder();

        $app['paging']->limit($limit);
        $app['paging']->process(1, $totalRecords);

        $offset = $app['paging']->offset();
        $limit  = $app['paging']->limit();

        $query = "SELECT $select FROM $from WHERE $where $group $order LIMIT $offset, $limit";

        $app->debug('getRecords = '.$query);

        return DB::select($query);
    }
}
