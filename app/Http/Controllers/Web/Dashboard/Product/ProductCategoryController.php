<?php

namespace App\Http\Controllers\Web\Dashboard\Product;

use App\DataTables\Product\ProductCategoryDataTable;
use App\Foundations\Controller;
use App\Http\Requests\Web\Dashboard\Product\StoreCategoryRequest;
use App\Http\Requests\Web\Dashboard\Product\UpdateCategoryRequest;
use App\Models\ProductCategory;
use App\Services\Web\Dashboard\Product\ProductCategoryService;
use App\Traits\Authorizable;

class ProductCategoryController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private ProductCategoryService $service,
        private $policy = ProductCategory::class,
        private $abilities = [
            'index' => 'viewAny',
            'store' => 'store',
            'update' => 'update',
            'destroy' => 'delete',
        ]
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(ProductCategoryDataTable $dataTable)
    {
        return $dataTable->render('pages.dashboard.product.category.index', $this->service->index());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        if (!$this->service->store($request->validated())) {
            return $this->sendResponse(null, 'Failed to create product category. Please try again.', 500);
        }

        return $this->sendResponse(null, 'Product category successfully created.', 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, ProductCategory $productCategory)
    {
        if (!$this->service->update($request->validated(), $productCategory)) {
            return $this->sendResponse(null, 'Failed to update product category. Please try again.', 500);
        }

        return $this->sendResponse(null, 'Product category successfully updated.', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductCategory $productCategory)
    {
        if (!$this->service->destroy($productCategory)) {
            return $this->sendResponse(null, 'Failed to delete product category. Please try again.', 500);
        }

        return $this->sendResponse(null, 'Product category successfully deleted.', 200);
    }
}
