<?php

namespace App\Services\Web\Landing;

use App\Enums\ReportType;
use App\Foundations\Service;
use App\Models\Information;
use App\Models\Report;

class HomeService extends Service
{
    /**
     * Handle the incoming request.
     */
    public function invoke(): array
    {
        $reports = Report::latest()->take(6)->with('category:id,name', 'user:id', 'user.personal:id,user_id,first_name,last_name')->get();

        $reportIcons = [];
        foreach (ReportType::cases() as $type) {
            $reportIcons[$type->value] = [
                'icon' => $type->icon(),
                'text' => $type->label(),
                'bg' => $type->bgColor(),
            ];
        }

        $informations = Information::latest()->take(6)->with('category:id,name', 'user:id', 'user.personal:id,user_id,first_name,last_name,avatar')->get();

        $isReportMoreThanSix = Report::count() > 6;
        $isInformationMoreThanSix = Information::count() > 6;

        $teams = [
            [
                'name' => 'Sya’bananta Faqih M L',
                'role' => 'Project Manager',
                'image' => 'img/front-pages/teams/syabananta-faqih-m-l.png',
                'bg_class' => 'bg-label-primary',
                'border_class' => 'border-primary-subtle',
            ],
            [
                'name' => 'Apri Pandu Wicaksono',
                'role' => 'Fullstack Developer',
                'image' => 'img/front-pages/teams/apri-pandu-w.png',
                'bg_class' => 'bg-label-info',
                'border_class' => 'border-info-subtle',
            ],
            [
                'name' => 'Muhammad Zaki Fauzan',
                'role' => 'UI/UX Designer',
                'image' => 'img/front-pages/teams/muhammad-zaki-f.png',
                'bg_class' => 'bg-label-danger',
                'border_class' => 'border-danger-subtle',
            ],
            [
                'name' => 'M Hamzah Haifan M',
                'role' => 'UI/UX Designer',
                'image' => 'img/front-pages/teams/muhammad-hamzah-h-m.png',
                'bg_class' => 'bg-label-success',
                'border_class' => 'border-success-subtle',
            ],
            [
                'name' => 'Alif Zaujati Randri',
                'role' => 'QA Engineer',
                'image' => 'img/front-pages/teams/alif-zaujati-r.png',
                'bg_class' => 'bg-label-warning',
                'border_class' => 'border-warning-subtle',
            ],
        ];

        return compact('reports', 'informations', 'reportIcons', 'isReportMoreThanSix', 'isInformationMoreThanSix', 'teams');
    }
}
