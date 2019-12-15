<?php

interface CrudFunction {
    public function getAll();
    public function getByPrimaryKey($primaryKey);
    public function insert($data);
    public function update($data,$primaryKey);
    public function delete($primaryKey);
}