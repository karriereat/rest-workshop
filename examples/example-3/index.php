<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require __DIR__ .'/../../vendor/autoload.php';

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
$app->get('/person/{id}', function (Request $request, Response $response, array $args) use ($personStore) {

    $id = $args['id'];
    $person = $personStore[$id];
    return $response->withJson($person);
});

$app->run();