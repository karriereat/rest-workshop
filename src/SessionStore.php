<?php

namespace Karriere\RestWorkshop;

class SessionStore implements StoreInterface
{

    /**
     * @var string
     */
    private $name;


    public function __construct(string $name)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $this->name = $name;
        if (!is_array($_SESSION[StoreInterface::STORE][$this->name])) {
            $_SESSION[StoreInterface::STORE][$this->name] = [];
        }
    }

    public function getAll(): array
    {
        return array_values($_SESSION[StoreInterface::STORE][$this->name]);
    }

    public function save($id, $data)
    {
        $_SESSION[StoreInterface::STORE][$this->name][$id] = $data;
    }

    public function get(int $id)
    {
        return $_SESSION[StoreInterface::STORE][$this->name][$id];
    }

    public function delete(int $id)
    {
        unset($_SESSION[StoreInterface::STORE][$this->name][$id]);
    }

    public function deleteAll()
    {
        unset($_SESSION[StoreInterface::STORE]);
    }

    public function getNextId(): int
    {
        $highestId = 0;
        foreach ($_SESSION[StoreInterface::STORE][$this->name] as $id => $data) {
            if ($id > $highestId) {
                $highestId = $id;
            }
        }

        return ++$highestId;
    }
}