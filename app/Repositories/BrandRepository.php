<?php


namespace App\Repositories;


use App\Contracts\BrandContract;
use App\Models\Brand;
use App\Traits\UploadAble;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\UploadedFile;
use PharIo\Manifest\InvalidEmailException;

/**
 * Class BrandRepository
 * @package App\Repositories
 */
class BrandRepository extends BaseRepository implements BrandContract
{

	use UploadAble;

	/**
	 * BrandRepository constructor.
	 * @param Model $model
	 */
	public function __construct(Brand $model) // It will be Brand $model not, Model $model
												// otherwise it will show error: Illuminate\Contracts\Container\BindingResolutionException
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
	public function listBrands(string $order = 'id', string $sort = 'desc', array $columns = ['*'])
	{
		return $this->all($columns, $order, $sort);
	}

	/**
	 * @param int $id
	 * @return mixed
	 */
	public function findBrandById(int $id)
	{
		try {

			return $this->findOneOrFail($id);

		} catch (ModelNotFoundException $e) {

			throw new ModelNotFoundException($e);

		}
	}

	/**
	 * @param array $params
	 * @return Brand|mixed
	 */
	public function createBrand(array $params)
	{

		try {
			$collection = collect($params);

			$logo = null;

			if ($collection->has('logo') && ($params['logo'] instanceof UploadedFile)) {
				$logo = $this->uploadOne($params['logo'], 'brands'); // using uploadAble trait
			}

			$merge = $collection->merge(compact('logo'));

			$brand = new Brand($merge->all());

			$brand->save();

			return $brand;
		} catch (QueryException $exception) {

			throw new InvalidEmailException($exception->getMessage());

		}
	}

	/**
	 * @param array $params
	 * @return mixed
	 */
	public function updateBrand(array $params)
	{
		$brand = $this->find($params['id']);

		$collection = collect($params)->except('_token');

		if ($collection->has('logo') && ($params['logo'] instanceof UploadedFile)) {

			if ($brand->logo != null) {
				$this->deleteOne($brand->logo);
			}

			$logo = $this->uploadOne($params['logo'], 'brands');

		}

		$merge = $collection->merge(compact('logo'));

		$brand->update($merge->all());

		return $brand;

	}

	/**
	 * @param $id
	 * @return mixed
	 */
	public function deleteBrand($id)
	{

		$brand = $this->findBrandById($id);

		if ($brand->logo != null) {
			$this->deleteOne($brand->logo);
		}

		$brand->delete();

		return $brand;

	}
}