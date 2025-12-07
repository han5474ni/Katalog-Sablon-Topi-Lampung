<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
<<<<<<< HEAD
if (file_exists($maintenance = __DIR__.'/storage/framework/maintenance.php')) {
=======
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
>>>>>>> master6
    require $maintenance;
}

// Register the Composer autoloader...
<<<<<<< HEAD
require __DIR__.'/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/bootstrap/app.php';
=======
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';
>>>>>>> master6

$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
$request = \Illuminate\Http\Request::capture();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
