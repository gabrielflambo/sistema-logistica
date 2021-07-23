<?php

require __DIR__ . '/../vendor/autoload.php';

use Template\Controller\Routine;

// Injetor de Dependencia Doctrine
$container = require __DIR__ . '/dependencies.php';

$routine = new Routine($container);
$routine->index();