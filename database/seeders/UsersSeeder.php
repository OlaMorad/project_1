<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'name' => 'aya',
            'email' => 'aya77habib@gmail.com',
            'phone' => '0983690313',
            'password' => bcrypt('aya77habib2007')
        ]);
        User::create([
            'name' => 'mira',
            'email' => 'mira88sy@gmail.com',
            'phone' => '0987654321',
            'password' => bcrypt('mira88sy2003')
        ]);
    }
}
