<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;

$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);

$app->get('/', function($requset, $response) {
    $response->getBody()->write('Welcome to Slim!');
    return $response;
});

$app->run();
