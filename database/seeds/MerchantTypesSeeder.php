<?php

use Illuminate\Database\Seeder;

class MerchantTypesSeeder extends Seeder
{

    public function run()
    {
        \App\Models\MerchantType::create([
            'name' => [
                'ar' => 'مطعم',
                'en' => 'Restaurant',
            ],
        ]);

        \App\Models\MerchantType::create([
            'name' => [
                'ar' => 'سوبر ماركت',
                'en' => 'Super Market',
            ],
        ]);
        \App\Models\MerchantType::create([
            'name' => [
                'ar' => 'أسرة منتجة',
                'en' => 'Productive family',
            ],
        ]);
        \App\Models\MerchantType::create([
            'name' => [
                'ar' => 'صيدلية',
                'en' => 'Pharmacy',
            ],
        ]);
    }
}
