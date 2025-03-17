<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;
use Carbon\Carbon;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'setting_key' => 'site_name',
                'setting_label' => 'Site Name',
                'setting_value' => 'My Website',
                'description' => 'The name of the website.',
                'is_default' => 1
            ],
            [
                'setting_key' => 'contact_email',
                'setting_label' => 'Contact Email',
                'setting_value' => 'contact@mywebsite.com',
                'description' => 'Email address for general inquiries.',
                'is_default' => 1
            ],
            [
                'setting_key' => 'company_address',
                'setting_label' => 'Company Address',
                'setting_value' => '123 Main Street, City, Country',
                'description' => 'The physical address of the company.',
                'is_default' => 1
            ],
            [
                'setting_key' => 'support_email',
                'setting_label' => 'Support Email',
                'setting_value' => 'support@mywebsite.com',
                'description' => 'Email address for support inquiries.',
                'is_default' => 1
            ],
            [
                'setting_key' => 'support_mobile',
                'setting_label' => 'Support Mobile',
                'setting_value' => '+1234567890',
                'description' => 'Support phone number.',
                'is_default' => 1
            ],
            [
                'setting_key' => 'check_match_video',
                'setting_label' => 'Check & Match Video',
                'setting_value' => '',
                'description' => 'Check & Match Video',
                'is_default' => 1
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrInsert(
                ['setting_key' => $setting['setting_key']],
                array_merge($setting, [
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ])
            );
        }
    }
}
