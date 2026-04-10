<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{

    public function run(): void
    {
        Admin::create([
         'first_name'=>'ali',
         'last_name'=>'ali',
         'email'=>'fakhrei@hmail.com',
         'password'=>Hash::make('12345678'),
         'role'=>'super_admin',
         'phone_number'=>'99414',
        ]);
    }
}
