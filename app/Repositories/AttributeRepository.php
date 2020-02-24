<?php


namespace App\Repositories;


use App\Contracts\AttributeContract;
use App\Models\Attribute;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use InvalidArgumentException;

class AttributeRepository extends BaseRepository implements AttributeContract
{

    public function __construct(Attribute $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    /**
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return mixed
     */
    public function listAttributes(string $order = 'id', string $sort = 'desc', array $columns = ['*'])
    {
        return $this->all($columns, $order, $sort);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function findAttributeById(int $id)
    {
        try {

            return $this->findOneOrFail($id);

        } catch (ModelNotFoundException $e) {

            throw new ModelNotFoundException($e);

        }
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function createAttribute(array $params)
    {

        try {

            $collection = collect($params);

            $is_filterable = $collection->has('is_filterable') ? 1 : 0;
            $is_required   = $collection->has('is_required') ? 1 : 0;

            $merge = $collection->merge(compact('is_filterable', 'is_required'));

            $attribute = new Attribute($merge->all());

            $attribute->save();

            return $attribute;

        } catch (QueryException $exception) {

            throw new InvalidArgumentException($exception->getMessage());

        }

    }

    /**
     * @param array $params
     * @return mixed
     */
    public function updateAttribute(array $params)
    {
        $attribute = $this->findAttributeById($params['id']);

        $collection = collect($params)->except('_token');

        $is_filterable = $collection->has('is_filterable') ? 1 : 0;
        $is_required   = $collection->has('is_required') ? 1 : 0;

        $merge = $collection->merge(compact('is_filterable', 'is_required'));

        $attribute->update($merge->all());

        return $attribute;

    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteAttribute($id)
    {
        $attribute = $this->findAttributeById($id);

        $attribute->delete();

        return $attribute;
    }
}