<?php

namespace Karriere\RestWorkshop;

interface StoreInterface
{
    const STORE = 'collections';

    public function getAll() : array;

    public function save($id, $data);

    public function get(int $id);

    public function delete(int $id);

    public function deleteAll();

    public function getNextId() : int;
}