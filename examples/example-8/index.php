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
    public $created;

    public function __construct($id, $firstname, $lastname)
    {
        $this->id = $id;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->created = date(DATE_ATOM);
    }

    public static function fromArray(array $data): Person
    {
        $person = new Person($data['id'], $data['firstname'], $data['lastname']);
        $person->created = date(DATE_ATOM);
        return $person;

    }
}

class PersonHandler
{
    const ENDPOINT = 'person';

    private $store;
    /**
     * @var \Slim\App
     */
    private $app;

    public function __construct(StoreInterface $store, \Slim\App $app)
    {
        $this->store = $store;
        $this->app = $app;
    }

    public function getAll(Request $request, Response $response, array $args)
    {
        return $response->withJson($this->store->getAll());
    }

    public function get(Request $request, Response $response, array $args)
    {
        $id = $args['id'];

        $person = $this->store->get($id);

        if ($person != null) {
            return $response->withJson($this->store->get($id));
        } else {
            return $this->errorNotFound($response);
        }
    }

    public function post(Request $request, Response $response, array $args)
    {
        $personData = $request->getParsedBody();

        if ($personData == null) {
            return $this->errorInvalidRequest($response);
        }

        $id = $this->store->getNextId();
        $personData['id'] = $id;
        $person = Person::fromArray($personData);

        if ($person->firstname == null || $person->lastname == null) {
            return $this->errorInvalidRequest($response);
        }

        $this->store->save($id, $person);

        $response = $response->withHeader(
            "location",
            $this->app->getContainer()->get('router')->pathFor('get-person', ['id' => $id])
        );
        return $response->withJson($person, 201);
    }

    public function put(Request $request, Response $response, array $args)
    {
        $personData = $request->getParsedBody();
        $id = $args['id'];

        if ($personData == null || $id == null) {
            return $this->errorInvalidRequest($response);
        }

        $person = $this->store->get($id);

        if ($person == null) {
            return $this->errorNotFound($response);
        }

        $person->firstname = $personData['firstname'];
        $person->lastname = $personData['lastname'];

        if ($person->firstname == null || $person->lastname == null) {
            return $this->errorInvalidRequest($response);
        }

        $this->store->save($id, $person);

        return $response->withJson($person);
    }

    public function delete(Request $request, Response $response, array $args)
    {
        $id = $args['id'];
        $person = $this->store->get($id);

        if ($person) {
            $this->store->delete($id);

            return $response->withJson([], 204);
        } else {
            return $this->errorNotFound($response);
        }
    }

    public function errorNotFound(Response $response)
    {
        return $response->withJson(['error' => "Person not found"], 404);
    }

    public function errorInvalidRequest(Response $response)
    {
        return $response->withJson(['error' => "Bad Request"], 400);
    }
}

/** =============================================================================== */

$store = new SessionStore('person');
if (count($store->getAll()) == 0) {
    $store->save(1, new Person(1, "Dipper", "Pines"));
    $store->save(2, new Person(2, "Mabel", "Pines"));
}

$app = new \Slim\App;

$personHandler = new PersonHandler($store, $app);

$app->get('/person/{id}', [$personHandler, 'get'])->setName('get-person');
$app->put('/person/{id}', [$personHandler, 'put']);
$app->delete('/person/{id}', [$personHandler, 'delete']);
$app->get('/person', [$personHandler, 'getAll']);
$app->post('/person', [$personHandler, 'post']);

$app->post('/clear', function (Request $request, Response $response, array $args) {
    unset($_SESSION[StoreInterface::STORE]);
});

$app->run();

