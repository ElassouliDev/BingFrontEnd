<?php

use Illuminate\Database\Seeder;
use  \App\Models\Merchant;
use  \App\Models\Item;

class MerchantSeeder extends Seeder
{
    public function run()
    {
        $merchant1 = Merchant::create([
            'name' => [
                'ar' => 'def-Merchant',
                'en' => 'def-Merchant',
            ],
//            'drivers_code' => '12345',//generateRandomString(4) . '1',
            'phone' => PHONE_MERCHANT1,
            'email' => EMAIL_MERCHANT1,
            "lng" => 31.5389666,//Al Quds Open University - Northern Gaza
            "lat" => 34.5072628,//Al Quds Open University - Northern Gaza
            'accepted' => true,
            'max_orders' => 5,
            'password' => \Illuminate\Support\Facades\Hash::make(PASSWORD),
            'status' => Merchant::ACTIVE,
            'city_id' => \App\Models\City::inRandomOrder()->first()->id,
            'Merchant_type_id' => \App\Models\MerchantType::inRandomOrder()->first()->id,
        ]);
        $this->createMercahntBranches($merchant1, 3);
        $this->createOtherMerchants(4);
    }

    private function createMercahntBranches($merchant1, $counter = 1)
    {
        for ($i = 1; $i <= $counter; $i++) {
            $branch1 = \App\Models\Branch::create([
                'name' => [
                    'ar' => 'branch - ' . ($merchant1->id + $i),
                    'en' => 'branch - ' . ($merchant1->id + $i),
                ],
                'merchant_id' => $merchant1->id,
                'phone' => '+96600577700' . $counter,
                'isOpen' => true,
                'rate' => 4.5,
                'total_rates_number' => (25 + $i),
                'email' => 'branch' . ($merchant1->id + $i) . '@gmail.com',
                "lng" => (31.5389666 + $i),//Al Quds Open University - Northern Gaza
                "lat" => (34.5072628 + $i),//Al Quds Open University - Northern Gaza
                'isMainBranch' => \App\Models\Branch::mainBranch()->where('merchant_id', $merchant1->id)->count() == 0 ? true : false,
                'password' => \Illuminate\Support\Facades\Hash::make(PASSWORD),
                'status' => Merchant::ACTIVE,
                'accepted' => true,
                'city_id' => \App\Models\City::inRandomOrder()->first()->id,
            ]);
            $this->createBranchHourse($branch1, 7);
            $this->createBranchPointsPrivacy($branch1, 7);
            $this->createBranchClassifications($branch1, 7);
            $this->createBranchEmployees($branch1, 7);
            $this->createBranchRewards($branch1, 7);
            $this->createBranchOffers($branch1, 7);
        }
    }

    private function createBranchItems($merchant1, $branch1, $class1, $counter = 1)
    {
        for ($i = 1; $i <= $counter; $i++) Item::create([
            'name' => [
                'ar' => 'Item - ' . ($merchant1->id + $branch1->id + $class1->id + $i),
                'en' => 'Item - ' . ($merchant1->id + $branch1->id + $class1->id + $i),
            ],
            'description' => [
                'ar' => 'description - ' . $i,
                'en' => 'description - ' . $i,
            ],
            'calories' => $i + 5,
            'merchant_id' => $branch1->merchant_id,
            'branch_id' => $branch1->id,
            'classification_id' => $class1->id,
        ]);
    }


    private function createBranchHourse($branch1, $itemsNumber)
    {
        for ($i = 1; $i <= $itemsNumber; $i++) {
            $branch1->hours()->create([
                'day' => $i,
                'from' => '6:00:00',// \Carbon\Carbon::now()->format('H:i A'), // 8 am
                'to' => '23:00:00',// \Carbon\Carbon::now()->addHour(12)->format('H:i A'), // 4 pm
            ]);
        }
    }

    private function createBranchPointsPrivacy($branch1, $itemsNumber)
    {
        \App\Models\BranchPointsPrivacy::create([
            'branch_id' => $branch1->id,
            'new_order' => ($branch1->id + $itemsNumber),
            'when_merchant_late' => ($branch1->id + $itemsNumber),
            'rate_order' => ($branch1->id + $itemsNumber),
            'ready_01' => ($branch1->id + $itemsNumber),
            'ready_04' => ($branch1->id + $itemsNumber),
            'ready_plus_04' => ($branch1->id + $itemsNumber),
        ]);
    }

    private function createBranchClassificationItems($branch1, $classification, $itemsNumber)
    {
        for ($i = 1; $i <= $itemsNumber; $i++) {
            $classification->items()->create([
                'name' => [
                    'ar' => 'item' . $branch1->id . $classification->id . $i,
                    'en' => 'item' . $branch1->id . $classification->id . $i,
                ],
                'price' => $branch1->id + $classification->id + $i + $itemsNumber,
                'merchant_id' => $branch1->merchant_id,
                'branch_id' => $branch1->id,
            ]);
        }
    }


    private function createBranchClassifications($branch1, $itemsNumber)
    {
        for ($i = 1; $i <= $itemsNumber; $i++) {
            $classification = $branch1->classifications()->create([
                'name' => [
                    'ar' => 'classification' . $i,
                    'en' => 'classification' . $i,
                ],
                'merchant_id' => $branch1->merchant_id
            ]);
            $this->createBranchClassificationItems($branch1, $classification, 7);

        }
    }

    private function createBranchRewards($branch1, $itemsNumber)
    {
        for ($i = 1; $i <= $itemsNumber; $i++) {
            $item = $branch1->rewards()->create([
                'name' => [
                    'ar' => 'item' . $branch1->id . $itemsNumber . $i,
                    'en' => 'item' . $branch1->id . $itemsNumber . $i,
                ],
                'price' => $branch1->id + $itemsNumber + $i + $itemsNumber,
                'points' => $branch1->id + $itemsNumber + $i + $itemsNumber + $itemsNumber,
                'merchant_id' => $branch1->merchant_id,
                'branch_id' => $branch1->id,
            ]);
            $item->update([
                'uuid' => $item->branch_id . $item->merchant_id . '-' . date('Y') . date('m') . $item->id,
            ]);

        }
    }

    private function createBranchOffers($branch1, $itemsNumber)
    {
        for ($i = 1; $i <= $itemsNumber; $i++) $branch1->offers()->create([
            'name' => [
                'ar' => 'offer' . $branch1->id . $itemsNumber . $i,
                'en' => 'offer' . $branch1->id . $itemsNumber . $i,
            ],
            'description' => [
                'ar' => 'description' . $branch1->id . $itemsNumber . $i,
                'en' => 'description' . $branch1->id . $itemsNumber . $i,
            ],
            'merchant_id' => $branch1->merchant_id,
            'branch_id' => $branch1->id,
            'from' => \Carbon\Carbon::now(),
            'to' => \Carbon\Carbon::now()->addDays(3),
        ]);
    }

    private function createBranchEmployees($branch1, $itemsNumber)
    {
        for ($i = 1; $i <= $itemsNumber; $i++) $branch1->employees()->create([
            'name' => [
                'ar' => 'employee' . $i,
                'en' => 'employee' . $i,
            ],
            'email' => 'e' . $i . '@gmail.com',
            'verified' => true,
            'type' => \App\Models\User::type['EMPLOYEE'],
            'lat' => 31.5347908, //Indonesian hospital
            'lng' => 34.5102229,//Indonesian hospital
            'phone' => ($i == 1 && $branch1->id == 1) ? PHONE_EMPLOYEE1 : ('+9665' . getRandomPhoneNumber_8_digit()),
            'password' => \Illuminate\Support\Facades\Hash::make(PASSWORD),
        ]);
    }

    private function createOtherMerchants($itemsNumber)
    {
        for ($item = 1; $item <= $itemsNumber; $item++) {
            $merchant1 = Merchant::create([
                'name' => [
                    'ar' => 'def-Merchant' . $item,
                    'en' => 'def-Merchant' . $item,
                ],
                'phone' => '+966' . getRandomPhoneNumber_8_digit(),
                'email' => 'merchant' . $item . '@gmail.com',
                "lng" => 31.5389666,//Al Quds Open University - Northern Gaza
                "lat" => 34.5072628,//Al Quds Open University - Northern Gaza

                'password' => \Illuminate\Support\Facades\Hash::make(PASSWORD),
                'status' => Merchant::ACTIVE,
                'accepted' => true,
                'city_id' => \App\Models\City::inRandomOrder()->first()->id,
                'Merchant_type_id' => \App\Models\MerchantType::find($item)->id,
            ]);
            $this->createMercahntBranches($merchant1, 3);
        }
    }
}
