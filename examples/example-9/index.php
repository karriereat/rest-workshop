<?php

use Karriere\RestWorkshop\SessionStore;
use Karriere\RestWorkshop\StoreInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require __DIR__ . '/../../vendor/autoload.php';

interface ApiResource
{
    function getId();

    function getType();

    function getAttributes();
}


class Person implements ApiResource
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

    public function getAttributes()
    {
        return [
            "firstname" => $this->firstname,
            "lastname" => $this->lastname,
            "created" => $this->created,
        ];
    }

    function getId()
    {
        return $this->id;
    }

    function getType()
    {
        return 'person';
    }
}


class PersonHandler
{

    private $store;
    const ENDPOINT = 'person';
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
        return $this->getApiResponse($response, $this->store->getAll());
    }

    public function get(Request $request, Response $response, array $args)
    {
        $id = $args['id'];

        $person = $this->store->get($id);
        if ($person != null) {
            return $this->getApiResponse($response, $person);
        } else {
            return $this->getApiResponse($response, null, 404, $this->errorNotFound());
        }
    }

    public function post(Request $request, Response $response, array $args)
    {
        $personData = $request->getParsedBody();

        if ($personData == null) {
            return $this->getApiResponse($response, null, 400, $this->errorInvalidRequest());
        }

        $id = $this->store->getNextId();
        $personData['id'] = $id;
        $person = Person::fromArray($personData);

        $errors = [];

        if ($person->firstname == null) {
            $errors[] = $this->errorFieldMissing('firstname');
        }

        if ($person->lastname == null) {
            $errors[] = $this->errorFieldMissing('lastname');

        }

        if (count($errors) > 0) {
            return $this->getApiResponse($response, null, 400, $errors);
        }

        $this->store->save($id, $person);

        $response = $response->withHeader(
            "location",
            $this->app->getContainer()->get('router')->pathFor('get-person', ['id' => $id])
        );
        return $this->getApiResponse($response, null, 201);
    }

    public function put(Request $request, Response $response, array $args)
    {
        $personData = $request->getParsedBody();
        $id = $args['id'];

        if ($personData == null || $id == null) {
            return $this->getApiResponse($response, null, 400, $this->errorInvalidRequest());
        }

        $person = $this->store->get($id);

        if ($person == null) {
            return $this->getApiResponse($response, null, 404, $this->errorNotFound());
        }

        $person->firstname = $personData['firstname'];
        $person->lastname = $personData['lastname'];

        $errors = [];

        if ($person->firstname == null) {
            $errors[] = $this->errorFieldMissing('firstname');
        }

        if ($person->lastname == null) {
            $errors[] = $this->errorFieldMissing('lastname');

        }

        if (count($errors) > 0) {
            return $this->getApiResponse($response, null, 400, $errors);
        }

        $this->store->save($id, $person);

        return $this->getApiResponse($response, $person);
    }

    public function delete(Request $request, Response $response, array $args)
    {
        $id = $args['id'];
        $person = $this->store->get($id);

        if ($person) {
            $this->store->delete($id);

            return $this->getApiResponse($response, [], 204);
        } else {
            return $this->getApiResponse($response, [], 404, $this->errorNotFound());
        }
    }

    private function getApiResponse(Response $response, $data, int $status = 200, array $errors = []): Response
    {

        $apiResponse = [];
        $apiResponse['errors'] = $errors;

        if (is_array($data)) {
            foreach ($data as $d) {
                $resource = [
                    'id' => $d->getId(),
                    'type' => $d->getType(),
                    "attributes" => $d->getAttributes()
                ];

                $apiResponse['data'][] = $resource;
            }
        } elseif ($data == null) {
            $apiResponse['data'] = null;
        } else {
            $resource = [
                'id' => $data->getId(),
                'type' => $data->getType(),
                "attributes" => $data->getAttributes()
            ];
            $apiResponse['data'] = $resource;

        }

        $apiResponse = array_filter((array)$apiResponse);

        if(empty($apiResponse)){
            return $response->withStatus($status);
        }

        return $response->withJson($apiResponse, $status);
    }

    public function getError(int $code, string $name, array $context = []){
        return [
            "code" => $code,
            "name" => $name,
            "context" => $context,
        ];
    }

    private function errorInvalidRequest()
    {
        return $this->getError(01, 'Invalid Json');
    }

    private function errorNotFound()
    {
        return $this->getError(02, 'Resource not found');
    }

    private function errorFieldMissing($field)
    {
        return $this->getError(03, 'field missing', [
            'field' => $field
        ]);
    }
}

/** =============================================================================== */

session_start();

$store = new SessionStore('person');
if (count($store->getAll()) == 0) {
    $store->save(1, new Person(1, "Dipper", "Dipoer"));
    $store->save(2, new Person(2, "Mabel", "Mabel"));
}


$app = new \Slim\App;

$personHandler = new PersonHandler($store, $app);

$app->get('/person/{id}', [$personHandler, 'get'])->setName('get-person');
$app->put('/person/{id}', [$personHandler, 'put']);
$app->delete('/person/{id}', [$personHandler, 'delete']);
$app->get('/person', [$personHandler, 'getAll']);
$app->post('/person', [$personHandler, 'post']);

$app->post('/clear', function(Request $request, Response $response, array $args){
    unset($_SESSION[StoreInterface::STORE]);
});

$app->run();

