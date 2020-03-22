<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\CategoryContract;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Class CategoryController
 * @package App\Http\Controllers\Admin
 */
class CategoryController extends BaseController
{

    protected $categoryRepository;

    public function __construct(CategoryContract $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        $categories = $this->categoryRepository->listCategories();

        $this->setPageTitle('Categories', 'List of all categories');
        return view('admin.categories.index', compact('categories'));

    }

    public function create()
    {
        $categories = $this->categoryRepository->treeList();

        $this->setPageTitle('Categories', 'Create Category');
        return view('admin.categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name'      => 'required|max:191',
            'parent_id' => 'required|not_in:0',
            'image'     => 'mimes:jpg,jpeg,png|max:1000',
        ]);

        $params = $request->except('_token'); // why it is needed?

        $category = $this->categoryRepository->createCategory($params);

        if (!$category) {
            return $this->responseRedirectBack('Error occurred while creating category', 'error', true, true);
        }

        return $this->responseRedirect('admin.categories.index', 'Category added successfully', 'success', false, false);

    }

    public function edit($id)
    {
        $targetCategory = $this->categoryRepository->findCategoryId($id);
        $categories     = $this->categoryRepository->treeList();

        $this->setPageTitle('Categories', 'Edit Category : ' . $targetCategory->name);
        return view('admin.categories.edit', compact('categories', 'targetCategory'));
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name'      => 'required|max:191',
            'parent_id' => 'required|not_in:0',
            'image'     => 'mimes:jpg,jpeg,png|max:1000',
        ]);

        $params = $request->except('_token');

        $category = $this->categoryRepository->updateCategory($params);

        if (!$category) {
            return $this->responseRedirectBack('Error occured while updating category', 'error', true, true);
        }

        return $this->responseRedirectBack('Category updated successfully', 'success', false, false);

    }

    public function delete($id)
    {
        $category = $this->categoryRepository->deleteCategory($id);

        if (!$category) {
            return $this->responseRedirectBack('Error occurred while deleting category', 'error', true, true);
        }
        return $this->responseRedirectBack('Category deleted successfully   ', 'success', false, false);

    }

}
