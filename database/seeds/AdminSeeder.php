<?php

use App\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'super-admin',
            'email' => 'admin@product.test',
            'type' => \App\Constant\UserType::ADMIN,
            'password' => bcrypt('12345678'),
        ]);
    }
}
