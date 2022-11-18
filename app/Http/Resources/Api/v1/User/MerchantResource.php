<?php

namespace App\Http\Resources\Api\v1\User;

use App\Http\Resources\Api\v1\CategoryResource;
use App\Http\Resources\Api\v1\CityResource;
use App\Models\ClientOrderRate;
use Illuminate\Http\Resources\Json\JsonResource;

class MerchantResource extends JsonResource
{
    public function toArray($request)
    {

        $except_arr_resource = $request['except_arr_resource'];
        $response = [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->image,
            'phone' => $this->phone,
            'email' => $this->email,
            'status' => $this->status,
            'status_name' => $this->status_name,
            'gender' => $this->gender,
            'gender_name' => gender($this->gender),
            'code' => $this->generatedCode,
            'distance' => $this->distance,
            'lng' => $this->lng,
            'lat' => $this->lat,

            'address' => $this->address,
            'cover' => $this->cover,
            'busy' => (bool)$this->busy,
            'open' => (bool)$this->open,
            'min_price' => (float)$this->min_price,
            'has_discount' => $this->is_discount,
            'max_discount' => $this->max_discount,
            'work_hour_today' => $this->work_hour_today,
            'rate' => [
                'avg_rate' => number_format($this->rate, 1),
                'total_rantings_number' => ClientOrderRate::where('branch_id', $this->id)->count(),
                'ranting_numbers' => [
                    "first" => ClientOrderRate::where('branch_id', $this->id)->where('stars_number', 1)->count(),
                    'second' => ClientOrderRate::where('branch_id', $this->id)->where('stars_number', 2)->count(),
                    'third' => ClientOrderRate::where('branch_id', $this->id)->where('stars_number', 3)->count(),
                    'fourth' => ClientOrderRate::where('branch_id', $this->id)->where('stars_number', 4)->count(),
                    'fifth' => ClientOrderRate::where('branch_id', $this->id)->where('stars_number', 5)->count(),
                ],
            ],


        ];
            if (!is_array($except_arr_resource) || !in_array('city', $except_arr_resource)) {
                $response['city'] = new CityResource($this->city);
            }

            if (!is_array($except_arr_resource) || !in_array('providerType', $except_arr_resource)) {
                $response['merchant_type'] = new MerchantTypeResource($this->merchantType);
            }



        return $response;
    }
}
