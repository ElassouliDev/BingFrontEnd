<?php

use Illuminate\Database\Seeder;

class BankTableSeeder extends Seeder
{

    public function run()
    {
        App\Models\Bank::create([
            'name' => [
                'ar' => 'بنك الراجحي',
                'en' => 'alrajhy bank',
            ],
        ]);

        for ($i = 0; $i <= 3; $i++){
            \App\Models\Bank::create([
                'name' => [
                    'ar' => 'Bank' . $i,
                    'en' => 'Bank' . $i,
                ]

            ]);
        }

    }
}
