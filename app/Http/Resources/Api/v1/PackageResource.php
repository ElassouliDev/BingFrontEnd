<?php

namespace App\Http\Resources\Api\v1;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
{
    public function toArray($request)
    {
        $except_arr_resource = $request['except_arr_resource'];
            $response = [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->image,
            'months' => $this->months,
            'price' => $this->price,
            'delivery_number' => $this->delivery_number,
            'km_limit' => $this->km_limit,
        ];
        if ($this->get_remaining_number && isset($this->get_user) && $this->get_user instanceof User) {
            $response['remaining_delivery_number'] = $this->delivery_number - count($this->get_user->orders);
        }
        return $response;
    }
}
