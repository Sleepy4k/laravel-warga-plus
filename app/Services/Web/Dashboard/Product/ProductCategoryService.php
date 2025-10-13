<?php

namespace App\Services\Web\Dashboard\Product;

use App\Contracts\Models;
use App\Enums\ReportLogType;
use App\Foundations\Service;
use App\Models\ProductCategory;

class ProductCategoryService extends Service
{
    /**
     * Model contract constructor.
     */
    public function __construct(
        private Models\ProductCategoryInterface $productCategoryInterface,
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index(): array
    {
        return [];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param array $request
     *
     * @return bool
     */
    public function store(array $request): bool
    {
        try {
            $category = $this->productCategoryInterface->create([
                'name' => $request['name'],
                'label' => $request['label'],
            ]);

            return $category ? true : false;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to create product category: ' . $th->getMessage(), [
                'request' => $request,
            ]);
            return false;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param array $request
     * @param ProductCategory $productCategory
     *
     * @return bool
     */
    public function update(array $request, ProductCategory $productCategory): bool
    {
        try {
            $productCategory->update([
                'name' => $request['name'],
                'label' => $request['label'],
            ]);

            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to update product category: ' . $th->getMessage(), [
                'product_category_id' => $productCategory->id,
                'request' => $request,
            ]);
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ProductCategory $productCategory
     *
     * @return bool
     */
    public function destroy(ProductCategory $productCategory): bool
    {
        try {
            if ($productCategory->products()->count() > 0) {
                $productCategory->products()->detach();
            }

            $productCategory->delete();

            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to delete product category: ' . $th->getMessage(), [
                'product_category_id' => $productCategory->id,
            ]);
            return false;
        }
    }
}
