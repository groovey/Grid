<?php

require_once __DIR__.'/../vendor/autoload.php';

use Silex\Application;
use Groovey\ORM\Providers\ORMServiceProvider;
use Groovey\Grid\Providers\GridServiceProvider;

$app = new Application();
$app['debug'] = true;

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

$app->register(new GridServiceProvider(), [
        'grid.templates' => __DIR__.'/../templates/grid',
        'grid.cache'     => __DIR__.'/cache',
    ]);

$app['db']->connection();
$app['grid']->load('../config/sample.yml');

?>
<table class="" border="1" cellspacing="6" cellspacing="1">
    <thead>
        <?= $app['grid']->listing->render('header'); ?>
    </thead>
    <tbody>
        <?= $app['grid']->listing->render('body'); ?>
    </tbody>
</table>

