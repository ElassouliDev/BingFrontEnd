<?php

namespace Tests\Feature\Api\v1;

use App\Http\Resources\Api\v1\OrderStatusTimeLineResource;
use App\Models\Address;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class AuthTest extends TestCase
{
//    use RefreshDatabase;

    /** @test */
    public function new_user_logged_in()
    {
        dd(serialize(Address::first()),unserialize(serialize(Address::first())));

        dd( \App\Models\Branch::count());
        dd( \App\Models\Branch::latest()/*->dd()*/->get()->last());
//        create objects
        $obj1 = getAnonymousStatusObj(Order::PENDING,'PENDING',Carbon::now()->format(DATE_FORMAT_FULL));
        $obj2 = getAnonymousStatusObj(Order::COMPLETED,'COMPLETED',Carbon::now()->format(DATE_FORMAT_FULL));
//        store them as array
        $arr = [$obj1, $obj1, $obj2, $obj2];
//        encode these objects
        $arnew = json_encode($arr);
//        $status_time_line = OrderStatusTimeLineResource::collection(json_decode($arnew));
//decoding
        $decoded = json_decode($arnew);
//        get objects in a new form and update them as you like
        $newColll =  collect($decoded)->map(function ($item){
            if ($item->key == Order::PENDING){
                $item->key = Order::ACCEPTED;
                $item->key_name = 'Order::ACCEPTED';
            }
            return $item;
        });
//        after that convert them as array and restore them
        $arr = $newColll->toArray();
        dd(in_array($newObj->key,collect($decoded)->pluck('key')->toArray()));
//        dd(in_array($newObj,collect($arr)->pluck('key')->toArray()));

        $arr = addNewStatusToSerializedObj(getAnonymousStatusObj(Order::CANCELED, 'CANCELED'), $decoded);



        dd(OrderStatusTimeLineResource::collection($arr));
        /*
         *
         *
         */
        $this->withoutExceptionHandling();
        parent::setUp();
        Artisan::call('migrate', ['-vvv' => true]);
        Artisan::call('passport:install', ['-vvv' => true]);
        Artisan::call('db:seed', ['-vvv' => true]);

//        $attributes = [
//            'phone' => '+966555555555',
//            'fcm_token' => 'xxx',
//        ];
//        $this->post($this->getFullRoute('/login'),$this->withCSRFToken($attributes))->assertStatus(200);

        $attributes = [
            'phone' => PHONE_CLIENT1,
            'code' => '12345',
        ];
        $client = User::create([
            'name' => [
                'ar' => 'def-client',
                'en' => 'def-client',
            ],
            'email' => 'def-client@client.com',
            'type' => User::CLIENT,
            'status' => User::ACTIVE,
            'phone' => PHONE_CLIENT1,
            'generatedCode' => '12345',
            'verified' => true,
            'lat' => 24.57129,
            'lng' => 46.17737,
            'dob' => \Carbon\Carbon::now(),
            'password' => \Illuminate\Support\Facades\Hash::make(PASSWORD),
        ]);
        \App\Models\Address::create([
            'user_id' => $client->id,
            'name' => 'name',
            'lat' => 21.88724,
            'lng' => 44.80889,
            'address' => 'address',
            'type' => \App\Models\Address::HOME,
            'default' => true,
        ]);

        $this->post($this->getFullRoute('/verified_code'), $this->withCSRFToken($attributes))->assertStatus(200);
    }
}
