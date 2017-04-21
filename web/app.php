<?php

use Infrastructure\Symfony\AppKernel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;

require_once __DIR__.'/../vendor/autoload.php';

$env = getenv('SYMFONY_ENV') ?: 'prod';
$debug = ('prod' !== $env);

if ($debug) {
    Debug::enable();
}

$kernel = new AppKernel($env, $debug);

$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
