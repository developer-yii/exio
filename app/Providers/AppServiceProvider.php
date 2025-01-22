<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            $connection = DB::connection()->getPdo();
            if ($connection) {
                $settings = Setting::all()->pluck('setting_value', 'setting_key')->toArray();
                Config::set(['settings' => $settings]);
            }
        } catch (\Exception $e) { }
    }
}
