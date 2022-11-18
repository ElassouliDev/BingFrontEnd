<?php

use Illuminate\Database\Seeder;

class CityTableSeeder extends Seeder
{
    public function run()
    {


        App\Models\City::create([
            'name' => [
                'ar' => 'الرياض',
                'en' => 'Riyadh',
            ],
        ]);

        App\Models\City::create([
            'name' => [
                'ar' => 'جدة',
                'en' => 'Jeddah city',
            ],
        ]);

    }
}
