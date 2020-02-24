<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\BrandContract;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

/**
 * Class BrandController
 * @package App\Http\Controllers\Admin
 */
class BrandController extends BaseController // I forgot to extend BaseController, that's why showing error below:
	// Illuminate\Contracts\Container\BindingResolutionException
	//Target [Illuminate\Database\Eloquent\Model] is not instantiable while building [App\Http\Controllers\Admin\BrandController, App\Repositories\BrandRepository].
{
	/**
	 * @var BrandContract
	 */
	protected $brandRepository;

	/**
	 * BrandController constructor.
	 * @param BrandContract $brandRepository
	 */
	public function __construct(BrandContract $brandRepository)
	{
		$this->brandRepository = $brandRepository;
	}

	public function index()
	{
		$brands = $this->brandRepository->listBrands();

		$this->setPageTitle('Brands', 'List of all brands');
		return view('admin.brands.index', compact('brands'));
	}

	public function create()
	{
		$this->setPageTitle('Brands', 'Create Brand');
		return view('admin.brands.create');
	}

	public function store(Request $request)
	{
		$this->validate($request, [
			'name'  => 'required|max:191',
			'image' => 'mimes:jpg,jpeg,png|max:1000'
		]);

		$params = $request->except('_token');

		$brand = $this->brandRepository->createBrand($params);

		if (!$brand) {
			return $this->responseRedirectBack('Error occurred while creating brand.', 'error', true, true);
		}

		return $this->responseRedirect('admin.brands.index', 'Brand added successfully', 'success', false, false);

	}

	public function edit($id)
	{

		$brand = $this->brandRepository->findBrandById($id);

		$this->setPageTitle('Brands', 'Edit Brand : ' . $brand->name);
		return view('admin.brands.edit', compact('brand'));

	}

	public function update(Request $request)
	{
		$this->validate($request, [
			'name'  => 'required|max:191',
			'image' => 'mimes:jpg,jpeg,png|max:1000'
		]);

		$params = $request->except('_token');

		$brand = $this->brandRepository->updateBrand($params);

		if (!$brand) {
			return $this->responseRedirectBack('Error occurred while updating brand.', 'error', true, true);
		}

		return $this->responseRedirectBack('Brand updated successfully', 'success', false, false);

	}

	public function delete($id)
	{
		$brand = $this->brandRepository->deleteBrand($id);

		if (!$brand) {
			return $this->responseRedirectBack('Error occurred while deleting brand.', 'error', true, true);
		}

		return $this->responseRedirect('admin.brands.index', 'Brand deleted successfully', 'success', false, false);
	}
}
