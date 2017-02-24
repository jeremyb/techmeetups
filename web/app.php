<?php

use Symfony\Component\HttpFoundation\Request;
use UI\Symfony\AppKernel;

require_once __DIR__.'/../vendor/autoload.php';

$env = getenv('SYMFONY_ENV') ?: 'prod';
$debug = ('prod' !== $env);

$kernel = new AppKernel($env, $debug);
$kernel->loadClassCache();
Request::enableHttpMethodParameterOverride();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
