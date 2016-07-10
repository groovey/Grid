<?php

require_once __DIR__.'/../vendor/autoload.php';

use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Groovey\ORM\Providers\ORMServiceProvider;
use Groovey\Grid\Providers\GridServiceProvider;
use Groovey\Form\Providers\FormServiceProvider;
use Groovey\Paging\Providers\PagingServiceProvider;
use Groovey\Support\Providers\RequestServiceProvider;
use Groovey\Config\Providers\ConfigServiceProvider;

class App extends Application
{
    use Groovey\Support\Traits\DebugTrait;
}

$app = new App();

$app['debug'] = true;

$app->register(new FormServiceProvider());
$app->register(new RequestServiceProvider());
$app->register(new GridServiceProvider());

$app->register(new ConfigServiceProvider(), [
        'config.path'        => __DIR__.'/../config',
        'config.environment' => 'localhost',
    ]);

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
// $_POST['q'] = 'man';
// $_POST['sort_field'] = 'name';
// $_POST['sort_order'] = 'desc';
$_POST['filter_status'] = 'active';


$app['config']->set('app.debug' , true);

$app['db']->connection();
$app['grid']->load('../resources/yaml/sample.yml');

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