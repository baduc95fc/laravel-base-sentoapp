<?php

namespace App\Repository\Eloquent;

use Illuminate\Container\Container as Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use App\Repository\Contracts\RepositoryInterface;

abstract class BaseRepository implements RepositoryInterface
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var array
     */
    protected $fieldSearchable = [];
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = null;

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->makeModel();
        $this->boot();
    }
    /**
     *
     */
    public function boot()
    {
    }

    /**
     * @throws RepositoryException
     */
    public function resetModel()
    {
        $this->makeModel();
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    abstract protected function model();

    /**
     * @return Model
     * @throws RepositoryException
     */
    public function makeModel()
    {
        $model = $this->app->make($this->model());

        if (!$model instanceof Model) {
            throw new RepositoryException("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }
        return $this->model = $model;
    }
    /**
     * Get Searchable Fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Retrieve data array for populate field select
     * Compatible with Laravel 5.3
     * @param string $column
     * @param string|null $key
     *
     * @return \Illuminate\Support\Collection|array
     */
    public function pluck($column, $key = null)
    {
        return $this->model->pluck($column, $key);
    }


    public function all($columns = ['*'])
    {
        if ($this->model instanceof Builder) {
            $results = $this->model->get($columns);
        } else {
            $results = $this->model->all($columns);
        }

        return $results;
    }


    public function first($columns = ['*'])
    {
        $results = $this->model->first($columns);
        return $results;
    }

    public function firstWhere(array $where, $columns = ['*'])
    {
        $result = $this->model->where($where)->get($columns)->first();
        return $result;
    }

    public function firstByField($field, $value, $columns = ['*'])
    {
        $result = $this->model->where($field, '=', $value)->get($columns)->first();
        return $result;
    }

    public function find($id, $columns = ['*'])
    {
        $model = $this->model->findOrFail($id, $columns);
        return $model;
    }

    /**
     * Find data by field and value
     *
     * @param       $field
     * @param       $value
     * @param array $columns
     *
     * @return mixed
     */
    public function findByField($field, $value = null, $columns = ['*'])
    {
        $model = $this->model->where($field, '=', $value)->get($columns);
        return $model;
    }

    public function countWhere(array $where, $columns = ['*'])
    {
        $model = $this->model->where($where)->count();
        return $model;
    }

    public function sumWhere(array $where, $columns)
    {
        $model = $this->model->where($where)->sum($columns);
        return $model;
    }
    /**
     * Find data by multiple fields
     *
     * @param array $where
     * @param array $columns
     *
     * @return mixed
     */
    public function findWhere(array $where, $take, $skip, $order = null, $orderby = null, $columns = ['*'])
    {
        $query = $this->model->where($where);
        $query = $this->buildOrderBy($query, $order, $orderby);
        $model =  $query->skip($skip)->take($take)->get($columns);
        return $model;
    }

    public function findWhereAll(array $where, $order = null, $orderby = null, $columns = ['*'])
    {
        $query = $this->model->where($where);
        $query = $this->buildOrderBy($query, $order, $orderby);
        $model = $query->get($columns);
        return $model;
    }

    protected function buildOrderBy($query, $order, $orderby)
    {
        if ($order != null && $order != '') {
            if ($orderby === null || $orderby == '') {
                $orderby = 'desc';
            }

            $query = $query->orderBy($order, $orderby);
        }
        return $query;
    }
    /**
     * Find data by multiple values in one field
     *
     * @param       $field
     * @param array $values
     * @param array $columns
     *
     * @return mixed
     */
    public function findWhereIn($field, array $values, $columns = ['*'])
    {
        $result = $this->model->whereIn($field, $values)->get($columns);
        return $result;
    }

    /**
     *
     *
     * @param       $field
     * @param array $values
     * @param array $columns
     *
     * @return mixed
     */
    public function findWhereAndWhereIn(array $where, $field, array $values, $columns = ['*'])
    {
        $result = $this->model->where($where)->whereIn($field, $values)->get($columns);
        return $result;
    }

    /**
     * Find data by excluding multiple values in one field
     *
     * @param       $field
     * @param array $values
     * @param array $columns
     *
     * @return mixed
     */
    public function findWhereNotIn($field, array $values, $columns = ['*'])
    {
        $result = $this->model->whereNotIn($field, $values)->get($columns);
        return $result;
    }

    /*
    * Save a new entity in repository
    *
    * @throws ValidatorException
    *
    * @param array $attributes
    *
    * @return mixed
    */
    public function create(array $attributes)
    {
        $model = $this->model->newInstance($attributes);
        $model->save();
        return $model;
    }
    /**
     * Update a entity in repository by id
     *
     * @throws ValidatorException
     *
     * @param array $attributes
     * @param       $id
     *
     * @return mixed
     */
    public function update(array $attributes, $id)
    {
        $model = $this->model->findOrFail($id);
        $model->fill($attributes);
        $model->save();
        return $model;
    }
    /**
     * Delete a entity in repository by id
     *
     * @param $id
     *
     * @return int
     */
    public function delete($id)
    {
        $model = $this->find($id);
        $deleted = $model->delete();
        return $deleted;
    }

    public function deleteByField($field, $value = null)
    {
        $model = $this->model->where($field, '=', $value);
        $deleted = $model->delete();
        return $deleted;
    }

    public function deleteLikeField($field, $value = null)
    {
        $deleted = $this->model
            ->where($field, 'like', '%' . $value . '%')
            ->delete();
        return $deleted;
    }

    public function findLikeField($field, $value = null, $columns = ['*'])
    {
        $model = $this->model
            ->where($field, 'like', '%' . $value . '%')
            ->get($columns);
        return $model;
    }

    public function firstLikeField($field, $value = null, $columns = ['*'])
    {
        $model = $this->model
            ->where($field, 'like', $value)
            ->get($columns)
            ->first();
        return $model;
    }

    /**
     * Insert multiple rows
     *
     * @param $dataInserts
     * @return bool
     */
    public function insertMultipleRows($dataInserts)
    {
        return $this->model->insert($dataInserts);
    }

    /**
     * update Or Create model
     *
     * @param $arrClause
     * @param $arrUpdate
     * @return mixed
     */
    public function updateOrCreate($arrClause, $arrUpdate)
    {
        return $this->model->updateOrCreate($arrClause, $arrUpdate);
    }

    /**
     * update Multiple Rows
     *
     * @param $arrClause
     * @param $arrUpdate
     * @return mixed
     */
    public function updateMultipleRows($arrClause, $arrUpdate)
    {
        return $this->model->where($arrClause)->update($arrUpdate);
    }

    /**
     * update Multiple Rows by field id
     *
     * @param $arrayClauseByID
     * @param $arrayUpdate
     * @return mixed
     */
    public function updateMultipleRowsByID($arrayClauseByID, $arrayUpdate)
    {
        return $this->model->whereIn('id', $arrayClauseByID)->update($arrayUpdate);
    }

    /**
     * Delete Multiple Rows by field id
     *
     * @param $arrayClauseByID
     * @param $arrayUpdate
     * @return mixed
     */
    public function deleteMultipleRows(array $ids)
    {
        $result = $this->model->whereIn('id', $ids)->delete();
        return $result;
    }

    /**
     * Update without timestamps
     *
     * @throws ValidatorException
     *
     * @param array $attributes, $id
     *
     * @return mixed
     */
    public function updateWithoutTimestamps(array $attributes, $id)
    {
        $model = $this->model->findOrFail($id);
        $model->fill($attributes);
        $model->timestamps = false;
        $model->save();
        return $model;
    }

    /**
     * Delete Multiple Rows
     *
     * @throws ValidatorException
     *
     * @param array $attributes, $id
     *
     * @return mixed
     */
    public function deleteWhere(array $where, $value = null)
    {
        $model = $this->model->where($where);
        $deleted = $model->delete();
        return $deleted;
    }
}

