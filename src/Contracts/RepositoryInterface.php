<?php
/**
 * Created by PhpStorm.
 * User: janez
 * Date: 2015. 09. 23.
 * Time: 22:28
 */

namespace Janez89\Repository\Contracts;


interface RepositoryInterface
{
    /**
     * Retrieve fist model from database
     * @param array $columns
     * @return Model
     */
    public function first($columns = ['*']);

    /**
     * Retrieve model from database
     * @param $id
     * @param array $columns
     * @return Model
     */
    public function find($id, $columns = ['*']);

    /**
     * Retrieve model by attribute from database
     * @param $column
     * @param $value
     * @param array $columns
     * @return Model
     */
    public function findBy($column, $value, $columns = ['*']);

    /**
     * count elements
     * @return int
     */
    public function count();

    /**
     * get new model instance
     * @param array $attributes
     * @return mixed
     */
    public function getNew(array $attributes = []);

    /**
     * Create one instance from model
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes = []);

    /**
     * update instance from model
     * @param array $attributes
     * @param $id
     * @return Model
     */
    public function update(array $attributes = [], $id = null);

    /**
     * save or update data in database
     * @param $modelOrArray
     * @return mixed
     */
    public function save($modelOrArray);

    /**
     * Retrieve all data from database
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all($columns = ['*']);

    /**
     * @param $ids
     * @throws OwnerContextException
     * @return bool
     */
    public function delete($ids);

    /**
     * Pageinate models.
     * @param int $page
     * @param int $perPage
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function paginate($page = 1, $perPage = 15, $columns = ['*']);
}