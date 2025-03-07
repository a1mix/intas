<?php

use App\Kernel;

require_once '../src/Kernel.php';

$params = parse_ini_file(__DIR__ . '/../conf.ini');

if ($params !== false) {
    $withMigrations = $params['withMigrations'];
    $withSeeders = $params['withSeeders'];
} else {
    $withMigrations = false;
    $withSeeders = false;
}

$map = [
    '/' => __DIR__ . '/../src/Pages/Hello.php',
    '/schedule' => __DIR__ . '/../src/Pages/Schedule.php',
    '/trip/add' => __DIR__ . '/../src/Pages/AddTrip.php',
    '/api/schedule' => __DIR__ . '/../src/Api/ScheduleController.php',
];

$path = strtok($_SERVER['REQUEST_URI'], '?');

if (isset($map[$path])) {
    ob_start();
    $kernel = new Kernel($map, $path, $withMigrations, $withSeeders);
    $kernel->start();

} else {
    include '../src/Pages/NotFound.php';
}
