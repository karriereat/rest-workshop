<?php

use Karriere\RestWorkshop\SessionStore;
use Karriere\RestWorkshop\StoreInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require __DIR__ . '/../../vendor/autoload.php';

class Person
{

    public $id;
    public $firstname;
    public $lastname;

    public function __construct($id, $firstname, $lastname)
    {
        $this->id = $id;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
    }

    public static function fromArray(array $data): Person
    {
        return new Person($data['id'], $data['firstname'], $data['lastname']);
    }
}

$store = new SessionStore('person');
if (count($store->getAll()) == 0) {
    $store->save(1, new Person(1, "Dipper", "Pines"));
    $store->save(2, new Person(2, "Mabel", "Pines"));
}


$personHandler = new PersonHandler($store);

$app = new \Slim\App;
$app->get('/person/{id}', [$personHandler, 'get']);
$app->get('/person', [$personHandler, 'getAll']);
$app->post('/person', [$personHandler, 'post']);

$app->post('/clear', function(Request $request, Response $response, array $args){
    unset($_SESSION[StoreInterface::STORE]);
});

$app->run();


class PersonHandler
{

    private $store;

    public function __construct(StoreInterface $store)
    {
        $this->store = $store;
    }

    public function getAll(Request $request, Response $response, array $args)
    {
        return $response->withJson($this->store->getAll());
    }

    public function get(Request $request, Response $response, array $args)
    {
        $id = $args['id'];
        return $response->withJson($this->store->get($id));
    }

    public function post(Request $request, Response $response, array $args)
    {
        $personData = $request->getParsedBody();
        $id = $this->store->getNextId();
        $personData['id'] = $id;
        $person = Person::fromArray($personData);
        $this->store->save($id, $person);

        return $response->withJson($person);
    }
}