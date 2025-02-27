<?php

namespace Database\Seeders;

use App\Models\CmsPages;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CmsPagesSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'page_name' => 'terms-condition',
                'page_label' => 'Terms and Conditions',
            ],
            [
                'page_name' => 'privacy-policy',
                'page_label' => 'Privacy Policy',
            ],
            [
                'page_name' => 'about-us',
                'page_label' => 'About Us',
            ],
        ];

        foreach ($pages as $page) {
            CmsPages::updateOrInsert(
                ['page_name' => $page['page_name']],
                $page
            );
        }
    }
}
