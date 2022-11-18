<?php

use Illuminate\Database\Seeder;

class ManagerTableSeeder extends Seeder
{

    public function run()
    {
        \App\Models\Manager::query()->create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt(123456),
        ]);
    }
}
