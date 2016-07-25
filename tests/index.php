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
        'paging.limit'      => 10,
        'paging.navigation' => 7,
    ]);

// Controller

$datas = [
    'filter_status'    => $app['request']->get('filter_status', 'INACTIVE'),
];

$app['config']->set('app.debug', true);
$app['db']->connection();
$app['grid']->load('../resources/yaml/users.yml', $datas);

$filter = $app['grid']->filter->render($hidden = false);
$header = $app['grid']->listing->render('header');
$body   = $app['grid']->listing->render('body');
$paging = $app['grid']->paging->render();
$entry  = $app['grid']->entry->render();

// View
?>
<?= $app['form']->open(['method' => 'get']); ?>
<?= $filter; ?>
<table class="" border="1" cellspacing="6" cellspacing="1">
    <thead>
        <?= $header; ?>
    </thead>
    <tbody>
        <?= $body; ?>
    </tbody>
</table>
<?= $paging; ?>

<table class="" border="1" cellspacing="6" cellspacing="1">
    <thead>
        <tr>
            <th>Parameters</th>
            <th>Data</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Q</td>
            <td><?= $app['form']->text('q', $app['request']->get('q', '')); ?></td>
        </tr>
        <tr>
            <td></td>
            <td><?= $app['form']->submit('Search'); ?></td>
        </tr>
    </tbody>
</table>

<?= $entry; ?>

<?= $app['form']->close(); ?>