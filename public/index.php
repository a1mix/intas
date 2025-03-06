<?php

use App\Kernel;

require_once '../src/Kernel.php';

$map = [
    '/' => __DIR__ . '/../src/Pages/Hello.php',
    '/schedule' => __DIR__ . '/../src/Pages/Scheduling.php',
];

$path = strtok($_SERVER['REQUEST_URI'], '?');

if (isset($map[$path])) {
    ob_start();
    $kernel = new Kernel($map, $path);
    $kernel->start();

} else {
    include '../src/Pages/NotFound.php';
}
