<?php

namespace App\Services\Web\Dashboard\Profile;

use App\Contracts\Models;
use App\Foundations\Service;

class AccountService extends Service
{
    /**
     * Model contract constructor.
     */
    public function __construct(
        private Models\ArticleInterface $articleInterface,
        private Models\ProductInterface $productInterface,
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index(): array
    {
        $user = auth('web')->user()->load('personal:user_id,first_name,last_name,whatsapp_number,telkom_batch,address,avatar');
        $products = $this->productInterface->all(['id', 'name', 'detail_id', 'category_id'], ['detail:id,price,is_available,rating', 'category:id,label'], [['user_id', '=', $user->id]]);

        $totalArticles = 0;
        if ($user->can('article.index')) {
            $totalArticles = $this->articleInterface->count();
        }

        $personal = $user->personal;
        return [
            'user' => $user,
            'role' => $user->getRoleNames()->first() ?? "Guest",
            'personal' => $personal,
            'products' => $products,
            'totalProducts' => $products->count(),
            'totalArticles' => $totalArticles,
        ];
    }
}
