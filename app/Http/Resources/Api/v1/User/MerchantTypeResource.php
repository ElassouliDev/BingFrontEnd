<?php

namespace App\Http\Resources\Api\v1\User;

use App\Http\Resources\Api\v1\CategoryResource;
use App\Http\Resources\Api\v1\CityResource;
use Illuminate\Http\Resources\Json\JsonResource;

class MerchantTypeResource extends JsonResource
{
    public function toArray($request)
    {

        $except_arr_resource = $request['except_arr_resource'];
        $response = [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->image,
        ];
        return $response;
    }
}
