<?php

use Karriere\RestWorkshop\SessionStore;
use Karriere\RestWorkshop\StoreInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require __DIR__ . '/../../vendor/autoload.php';

class Person {

    public $id;
    public $firstname;
    public $lastname;
    public $created;

    public function __construct($id, $firstname, $lastname)
    {
        $this->id = $id;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->created = date(DATE_ATOM);
    }

    public static function fromArray(array $data) : Person {
        $person = new Person($data['id'], $data['firstname'], $data['lastname']);
        $person->created = date(DATE_ATOM);
        return $person;

    }
}


class PersonHandler {

    private $store;

    public function __construct(StoreInterface $store)
    {
        $this->store = $store;
    }

    public function getAll (Request $request, Response $response, array $args)
    {
        return $response->withJson($this->store->getAll());
    }

    public function get (Request $request, Response $response, array $args)
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

    public function put(Request $request, Response $response, array $args)
    {
        $personData = $request->getParsedBody();
        $id = $args['id'];


        $person = $this->store->get($id);

        if($person){
            $person->firstname = $personData['firstname'];
            $person->lastname = $personData['lastname'];

            $this->store->save($id, $person);

            return $response->withJson($person);
        } else {
            return $response->withJson(['error' => "person with ID '$id' not found"]);
        }
    }

    public function delete(Request $request, Response $response, array $args)
    {
        $id = $args['id'];
        $person = $this->store->get($id);

        if($person){
            $this->store->delete($id);

            return $response->withJson([]);
        } else {
            return $response->withJson(['error' => "person with ID '$id' not found"]);
        }
    }
}

/** =============================================================================== */

$store = new SessionStore('person');
if (count($store->getAll()) == 0) {
    $store->save(1, new Person(1, "Dipper", "Pines"));
    $store->save(2, new Person(2, "Mabel", "Pines"));
}

$personHandler = new PersonHandler($store);

$app = new \Slim\App;
$app->get('/person/{id}', [$personHandler, 'get']);
$app->put('/person/{id}', [$personHandler, 'put']);
$app->delete('/person/{id}', [$personHandler, 'delete']);
$app->get('/person', [$personHandler, 'getAll']);
$app->post('/person', [$personHandler, 'post']);

$app->post('/clear', function(Request $request, Response $response, array $args){
    unset($_SESSION[StoreInterface::STORE]);
});


$app->run();