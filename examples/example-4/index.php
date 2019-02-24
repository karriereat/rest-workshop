<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require __DIR__ . '/../../vendor/autoload.php';

class Person {
    public $firstname;
    public $lastname;

    public function __construct($firstname, $lastname)
    {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
    }
}

/** =============================================================================== */

$personStore = [
    new Person("Dipper", "Pines"),
    new Person("Mabel", "Pines")
];

$app = new \Slim\App;
$app->get('/person', function (Request $request, Response $response, array $args) use ($personStore) {
    return $response->withJson($personStore);
});

$app->get('/person/{id}', function (Request $request, Response $response, array $args) use ($personStore) {

    $id = $args['id'];

    if(!array_key_exists($id, $personStore)){
        $response = new \Slim\Http\Response(404);
        return $response->withJson(["error" => "Person with id '$id' does not exist"]);
    }

    $person = $personStore[$id];
    return $response->withJson($person);
});

$app->run();