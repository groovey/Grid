<?php

require_once __DIR__.'/../vendor/autoload.php';

use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Groovey\ORM\Providers\ORMServiceProvider;
use Groovey\Grid\Providers\GridServiceProvider;
use Groovey\Form\Providers\FormServiceProvider;
use Groovey\Paging\Providers\PagingServiceProvider;

$app = new Application();
$app['debug'] = true;

$app->register(new GridServiceProvider());
$app->register(new FormServiceProvider());
$app->register(new TwigServiceProvider(), [
        'twig.path' => __DIR__.'/../templates',
    ]);

$app->register(new ORMServiceProvider(), [
        'db.connection' => [
            'host'      => 'localhost',
            'driver'    => 'mysql',
            'database'  => 'test',
            'username'  => 'root',
            'password'  => '',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'logging'   => true,
        ],
    ]);

$app->register(new PagingServiceProvider(), [
        'paging.limit' => 10,
        'paging.navigation' => 7,
    ]);

// Testing
$_POST['sort_field'] = 'u.name';
$_POST['sort_order'] = 'asc';
$_POST['filter_status'] = 'active';

// $app['paging']->limit(10);
// $app['paging']->process(1, 100);

// $offset = $app['paging']->offset();
// $limit  = $app['paging']->limit();

// echo $app['paging']->render();

$app['db']->connection();
$app['grid']->load('../config/sample.yml');

echo $app['grid']->filter->render();

?>
<table class="" border="1" cellspacing="6" cellspacing="1">
    <thead>
        <?= $app['grid']->listing->render('header'); ?>
    </thead>
    <tbody>
        <?= $app['grid']->listing->render('body'); ?>
    </tbody>
</table>

<?= $app['grid']->paging->render(); ?>


