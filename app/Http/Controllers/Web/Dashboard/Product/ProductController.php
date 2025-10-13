<?php

namespace App\Http\Controllers\Web\Dashboard\Product;

use App\DataTables\Product\ProductDataTable;
use App\Foundations\Controller;
use App\Http\Requests\Web\Dashboard\Product\StoreRequest;
use App\Http\Requests\Web\Dashboard\Product\UpdateRequest;
use App\Models\Product;
use App\Services\Web\Dashboard\Product\ProductService;
use App\Traits\Authorizable;

class ProductController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private ProductService $service,
        private $policy = Product::class,
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
    public function index(ProductDataTable $dataTable)
    {
        return $dataTable->render('pages.dashboard.product.index', $this->service->index());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if (!$this->service->store($request->validated())) {
            return $this->sendResponse(null, 'Failed to create product. Please try again.', 500);
        }

        return $this->sendResponse($this->service->getProductStats(), 'Product successfully created.', 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Product $product)
    {
        if (!$this->service->update($request->validated(), $product)) {
            return $this->sendResponse(null, 'Failed to update product. Please try again.', 500);
        }

        return $this->sendResponse($this->service->getProductStats(), 'Product successfully updated.', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if (!$this->service->destroy($product)) {
            return $this->sendResponse(null, 'Failed to delete product. Please try again.', 500);
        }

        return $this->sendResponse($this->service->getProductStats(), 'Product successfully deleted.', 200);
    }
}
