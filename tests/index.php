<?php

require_once __DIR__.'/../vendor/autoload.php';

use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Groovey\ORM\Providers\ORMServiceProvider;
use Groovey\Grid\Providers\GridServiceProvider;
use Groovey\Form\Providers\FormServiceProvider;

$app = new Application();
$app['debug'] = true;

$app->register(new GridServiceProvider());
$app->register(new FormServiceProvider());
$app->register(new TwigServiceProvider(), [
    'twig.path' => [
            __DIR__.'/../templates/grid',
        ],
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

