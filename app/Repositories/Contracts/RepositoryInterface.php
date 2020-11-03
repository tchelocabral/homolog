<?php

namespace App\Repositories\Contracts;

interface RepositoryInterface{

    public function findAll();
    public function paginate($totalPage = 10);
    public function store(array $data);
    public function update($id, array $data);
    public function delete($id);

    // public function findById($id);
    // public function findBy($column, $value);
    // public function findOneBy($column, $value);

}