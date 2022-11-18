<?php

namespace App\Http\Resources\Api\v1;

use App\Http\Resources\Api\v1\User\MerchantResource;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderStatusTimeLineResource extends JsonResource
{

    public function toArray($request)
    {
        $except_arr_resource = $request['except_arr_resource'];
        return [
            'key' => $this->key,
            'key_name' => $this->key_name,
            'time' => Carbon::parse($this->value)->format(TIME_FORMAT_WITHOUT_SECONDS),
            'date' => (lang() == 'ar')? arabic_date(Carbon::now()) : english_date(Carbon::now()),
        ];
    }
}
