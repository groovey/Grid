<?php

require_once __DIR__.'/vendor/autoload.php';

use Groovey\Grid\Grid;

$grid = new Grid();


$grid->load('./config/sample.yml');


print $grind->render('listing');


