<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        if(User::where('email', 'admin@gmail.com')->first() == null){
            User::create([
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('12345678'),
                'role_type' => 1,
                'status' => 1,
            ]);
        }
    }
}
