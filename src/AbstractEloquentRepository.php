<?php
/**
 * Created by PhpStorm.
 * User: janez
 * Date: 2015. 09. 23.
 * Time: 22:33
 */

namespace Janez89\Repository;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use Janez89\Repository\Contracts\RepositoryInterface;
use Janez89\Repository\Traits\CriteriaHelper;
use Janez89\Repository\Traits\EloquentRepositoryHelper;
use Janez89\Repository\Traits\ScopeHelper;

abstract class AbstractEloquentRepository implements RepositoryInterface
{
    use EloquentRepositoryHelper, CriteriaHelper, ScopeHelper;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * AbstractEloquentRepository constructor.
     */
    public function __construct()
    {
        $this->initModel();
        $this->boot();
    }

    abstract public function getModelClass();

    protected function boot() { }

    /**
     * init model
     * @return void
     */
    protected function initModel()
    {
        $this->model = $this->getModel();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function getModel()
    {
        $model = $this->getModelClass();
        if ($this->isEloquentModel($model))
            return $model;

        return new $model;
    }

    /**
     * Query based Context. Alias getModelContext method
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function getQuery()
    {
        $query = $this->model->newQuery();

        $this->applyCriteria($query);
        $this->applyScope($query);

        return $query;
    }

    /**
     * Reset repository base state
     * @return $this
     */
    public function reset()
    {
        $this->clearCriteria();
        $this->clearScope();
        $this->initModel();
        $this->boot();

        return $this;
    }

    /**
     * @param $id
     * @param array $columns
     * @return mixed
     * @throws ModelNotFoundException
     */
    public function find($id, $columns = ['*'])
    {
        return $this->getQuery()->findOrFail($id, $columns);
    }

    /**
     * @param $column
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function findBy($column, $value, $columns = ['*'])
    {
        return $this->getQuery()->where($column, '=', $value)->get($columns);
    }

    /**
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all($columns = ['*'])
    {
        return $this->getQuery()->get($columns);
    }

    /**
     * @return int
     */
    public function count()
    {
        return (int) $this->getQuery()->count();
    }

    /**
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getNew(array $attributes = [])
    {
        return $this->getModel()->fill($attributes);
    }

    /**
     * @param array $attributes
     * @return static
     */
    public function create(array $attributes = [])
    {
        return $this->model->create($attributes);
    }


    /**
     * @param array $attributes
     * @param null $id
     * @return bool|int
     */
    public function update(array $attributes = [], $id = NULL)
    {
        $model = $id ?
            $this->model->where($this->getKeyName(), '=', $id) :
            $this->model;
        return $model->update($attributes);
    }

    /**
     * @param $modelOrArray
     * @return bool|int|AbstractEloquentRepository
     */
    public function save($modelOrArray)
    {
        if ($this->isEloquentModel($modelOrArray))
            return $modelOrArray->save();

        $modelOrArray = (array) $modelOrArray;

        if (isset($modelOrArray[$this->getKeyName()]))
            return $this->update($modelOrArray, $modelOrArray[$this->getKeyName()]);

        return $this->create($modelOrArray);
    }

    /**
     * @param $ids
     * @return mixed
     */
    public function delete($ids)
    {
        if (is_array($ids))
            return $this->getQuery()->whereIn($this->getKeyName(), $ids)->delete();

        return $this->getQuery()->where($this->getKeyName(), '=', $ids)->delete();
    }

    /**
     * @param int $page
     * @param int $perPage
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function paginate($page = 1, $perPage = 15, $columns = ['*'])
    {
        $page = $page > 0 ?: 1;
        $perPage = $perPage ?: 15;

        return $this->getQuery()
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get($columns);
    }
}