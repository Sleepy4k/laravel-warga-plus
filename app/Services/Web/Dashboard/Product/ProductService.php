<?php

namespace App\Services\Web\Dashboard\Product;

use App\Contracts\Models;
use App\Enums\ReportLogType;
use App\Foundations\Service;
use App\Models\Product;

class ProductService extends Service
{
    /**
     * Model contract constructor.
     */
    public function __construct(
        private Models\ProductInterface $productInterface,
        private Models\ProductDetailInterface $productDetailInterface,
        private Models\ProductCategoryInterface $productCategoryInterface,
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index(): array
    {
        $categories = $this->productCategoryInterface->all(['id', 'label']);

        if (isUserHasRole(config('rbac.role.highest'))) {
            $products = $this->productInterface->all(['id', 'detail_id'], ['detail:id,is_available']);
        } else {
            $products = $this->productInterface->all(['id', 'detail_id'], ['detail:id,is_available'], [['user_id', '=', auth('web')->id()]]);
        }

        $totalProduct = $products->count();
        $totalAvailable = $products->filter(function ($product) {
            return $product->detail->is_available;
        })->count();

        $totalNotAvailable = $products->count() - $totalAvailable;
        $totalCategory = $categories->count();

        return compact('categories', 'products', 'totalProduct', 'totalAvailable', 'totalNotAvailable', 'totalCategory');
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
            $detail = $this->productDetailInterface->create([
                'price' => $request['price'],
                'rating' => $request['rating'],
                'is_available' => $request['is_available'] ?? false,
                'image_url' => $request['image'],
                'location' => $request['location'] ?? null,
            ]);

            if (!$detail) {
                return false;
            }

            $product = $this->productInterface->create([
                'name' => ucfirst($request['name']),
                'description' => $request['description'],
                'category_id' => $request['category_id'],
                'detail_id' => $detail->id,
                'user_id' => auth('web')->id(),
            ]);

            if (!$product) {
                $this->productDetailInterface->deleteById($detail->id);
                return false;
            }

            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to create product: ' . $th->getMessage(), [
                'request' => $request,
            ]);
            return false;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param array $request
     * @param int $id
     *
     * @return bool
     */
    public function update(array $request, Product $product): bool
    {
        try {
            $data = [
                'price' => $request['price'],
                'rating' => $request['rating'],
                'is_available' => $request['is_available'] ?? false,
                'location' => $request['location'] ?? null,
            ];

            if (isset($request['image'])) {
                $data['image_url'] = $request['image'];
            }

            $product->detail->update($data);

            $product->update([
                'name' => ucfirst($request['name']),
                'description' => $request['description'],
                'category_id' => $request['category_id'],
            ]);

            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to update product: ' . $th->getMessage(), [
                'product_id' => $product->id,
                'request' => $request,
            ]);
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return bool
     */
    public function destroy(Product $product): bool
    {
        try {
            $product->load('detail');
            if ($product->detail) {
                $this->productDetailInterface->deleteById($product->detail->id);
            }
            $product->delete();

            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to delete product: ' . $th->getMessage(), [
                'product_id' => $product->id,
            ]);
            return false;
        }
    }

    /**
     * Get product statistics.
     *
     * @return array
     */
    public function getProductStats(): array
    {
        if (isUserHasRole(config('rbac.role.highest'))) {
            $products = $this->productInterface->all(['id', 'detail_id'], ['detail:id,is_available']);
        } else {
            $products = $this->productInterface->all(['id', 'detail_id'], ['detail:id,is_available'], [['user_id', '=', auth('web')->id()]]);
        }

        $totalProduct = $products->count();
        $totalAvailable = $products->filter(function ($product) {
            return $product->detail->is_available;
        })->count();

        $totalNotAvailable = $products->count() - $totalAvailable;
        $totalCategory = $this->productCategoryInterface->count();

        return compact('totalProduct', 'totalAvailable', 'totalNotAvailable', 'totalCategory');
    }
}
