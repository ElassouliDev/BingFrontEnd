<?php

namespace App\Http\Resources\Api\v1;

use App\Http\Resources\Api\v1\User\MerchantTypeResource;
use App\Models\ClientOrderRate;
use Illuminate\Http\Resources\Json\JsonResource;

class BranchResource extends JsonResource
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
            'open' => $this->open,
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
        ];
        return $response;
    }
}
