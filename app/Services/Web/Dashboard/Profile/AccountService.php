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
        private Models\ReportInterface $reportInterface,
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index(): array
    {
        $user = auth('web')->user()->load('personal:user_id,first_name,last_name,job,address,avatar');
        $personal = $user->personal;

        $reports = $this->reportInterface->all(
            ['id', 'title', 'location', 'status', 'category_id', 'user_id'],
            ['category:id,name'],
            [['user_id', $user->id]]
        ) ?? collect();

        return [
            'user' => $user,
            'role' => $user->getRoleNames()->first() ?? "Guest",
            'personal' => $personal,
            'reports' => $reports,
        ];
    }
}
