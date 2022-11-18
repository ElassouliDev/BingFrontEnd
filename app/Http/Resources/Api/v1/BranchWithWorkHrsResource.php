<?php

namespace App\Http\Resources\Api\v1;

use App\Http\Resources\Api\v1\User\MerchantTypeResource;
use App\Models\Branch;
use App\Models\ClientOrderRate;
use Illuminate\Http\Resources\Json\JsonResource;

class BranchWithWorkHrsResource extends JsonResource
{

    public function toArray($request)
    {
        $except_arr_resource = $request['except_arr_resource'];
        $distance = $this->distance;
        $speed = optional(getSettings('default_speed'))->value;
        $speed = isset($speed) ? $speed : 100;
        $time = (double)number_format(($distance / $speed), DECIMAL_DIGIT_NUMBER, DECIMAL_SEPARATOR, DIGIT_THOUSANDS_SEPARATOR);
        $response = [
            'id' => $this->id,
            'name' => $this->name,
            'open' => (bool)$this->open,
            'points' => optional($this->point)->points,
            'image' => $this->image,
            'cover' => $this->cover,
            'isMainBranch' => (bool)$this->isMainBranch,
            'phone' => $this->phone,
            'email' => $this->email,
            'status' => $this->status,
            'status_name' => $this->status_name,
            'distance' => $distance,
            'lng' => $this->lng,
            'lat' => $this->lat,
            'address' => $this->address,
            'time' => $time,
            'restaurant_branches' => BranchResource::collection(Branch::where('merchant_id', $this->merchant_id)->where('id','<>',$this->id)->get()),
            'hrs' => BranchWorkHrsResource::collection($this->hours),
        ];
        return $response;
    }
}
