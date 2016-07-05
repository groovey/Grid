<?php

require_once __DIR__.'/vendor/autoload.php';

use Groovey\Grid\Grid;

$grid = new Grid(
        $templatesPath = __DIR__. '/templates/grid',
        $cachePath     = __DIR__. '/cache'
    );


$grid->load('./config/sample.yml');

$datas = [
                ['id' => 1, 'name' => 'Sample 1'],
                ['id' => 2, 'name' => 'Sample 2'],
                ['id' => 3, 'name' => 'Sample 3'],
            ];

// $listingHtml = $grid->render

// print $grid->render('listing');
?>
<table class="" border="1" cellspacing="1" cellspacing="1">
    <thead>
        <?= $grid->renderListingHeader(); ?>
    </thead>
    <tbody>
        <tr>
            <td class="">Gecko</td>
            <!--  <td class="">Firefox 1.0</td>
            <td class="">Win 98+ / OSX.2+</td>
            <td class="center ">1.7</td>
            <td class="text-center">
                <button class="btn btn-default btn-rounded btn-outline btn-xs"><i class="fa fa-times"></i></button>
                <button class="btn btn-default btn-rounded btn-outline btn-xs"><i class="fa fa-check"></i></button>
            </td> -->
        </tr>
    </tbody>
</table>

