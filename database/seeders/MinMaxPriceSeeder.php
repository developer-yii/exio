<?php

namespace Database\Seeders;

use App\Models\GeneralSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MinMaxPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $minMaxPrice = [
            'min' => 1,
            'max' => 10000,
        ];

        GeneralSetting::updateOrCreate([
            'key' => 'min_price',
            'value' => $minMaxPrice['min'],
        ]);

        GeneralSetting::updateOrCreate([
            'key' => 'max_price',
            'value' => $minMaxPrice['max'],
        ]);
    }
}
