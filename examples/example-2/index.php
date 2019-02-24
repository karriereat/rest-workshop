<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../../vendor/autoload.php';

$app = new \Slim\App;
$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {

    $name = $args['name'];

    $body = [
        "message" => sprintf("Hello %s!", $name)
    ];

    $response = $response->withHeader('Content-type', 'application/json;charset=utf-8');
    $response->getBody()->write(json_encode($body));


    return $response;
});

$app->get('/hi/{name}', function (Request $request, Response $response, array $args) {

    $name = $args['name'];
    return $response->withJson(["message" => sprintf("Hi %s!", $name)]);
});

$app->run();