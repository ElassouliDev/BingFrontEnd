<?php

use App\Models\OrderStatusTimeLine;
use Illuminate\Database\Seeder;
use \App\Models\User;
use \App\Models\Order;
use \App\Models\Branch;
use App\Models\ClientOrderRate;
use App\Models\ClientDriverRate;
use App\Models\OrderItem;
use Carbon\Carbon;
use \App\Models\Delivery;


class ClientSeeder extends Seeder
{
    public function run()
    {
        for ($item = 1; $item <= 3; $item++) {
            $user = User::create([
                'name' => [
                    'ar' => 'client' . $item,
                    'en' => 'client' . $item,
                ],
                'email' => 'c' . $item . '@gmail.com',
                'verified' => true,
                'lat' => (31.5347908 + $item), //Indonesian hospital
                'lng' => (34.5102229 + $item),//Indonesian hospital
                'phone' => ($item == 1) ? PHONE_CLIENT1 : ('+9665' . getRandomPhoneNumber_8_digit()),
                'password' => \Illuminate\Support\Facades\Hash::make(PASSWORD),
            ]);
            $this->createUserOrders($user, 7);
            $this->createUserPoints($user);
        }
    }

    private function createUserOrders($user, $itemNumber = 2)
    {
        $branch = Branch::first();
        $classification = $branch->classifications()->first();
        $items = $classification->items;
        for ($i = 1; $i <= $itemNumber; $i++) {
            $order = $user->orders()->create([
                'branch_id' => $branch->id,
                'all_order_object_filled_out' => true,
                'employee_id' => $branch->employees->first()->id,
                'merchant_id' => $branch->merchant_id,
                'status' => Order::status['WORKING'],
            ]);
            $total = 0;
            foreach ($items as $index => $item) {
                $amount = (double)number_format((($index + 3) * $item->price), DECIMAL_DIGIT_NUMBER, DECIMAL_SEPARATOR, DIGIT_THOUSANDS_SEPARATOR);
                $quantity = (double)number_format(($index + 3), DECIMAL_DIGIT_NUMBER, DECIMAL_SEPARATOR, DIGIT_THOUSANDS_SEPARATOR);
                $order->order_items()->create([
                    'item_id' => $item->id,
                    'quantity' => $quantity,
                    'amount' => $amount,
                ]);
                $total += $amount;
            }
            $order->update([
                'total_cost' => $total,
                'meals_cost' => $total,
                'note' => 'note',
                'pick_up_time' => Carbon::now()->addDays(10),

                'uuid' => $order->branch_id . '-' . date('Y') . date('m') . $order->id,
            ]);
            $statusTimeLine = OrderStatusTimeLine::create([
                'order_id' => $order->id,
                'key' => Order::status['WORKING'],
                'key_name' => [
                    'ar' => api('Order Working', [], 'ar'),
                    'en' => api('Order Working', [], 'en'),
                ],
                'date' => Carbon::now()
            ]);

        }
    }

    private function createUserPoints($user)
    {
        foreach (Branch::get() as $index => $item) {
            \App\Models\UserMerchantPoints::create([
                'user_id' => $user->id,
                'branch_id' => $item->id,
                'points' => $user->id + $item->id + $index,
            ]);
        }
    }
}

