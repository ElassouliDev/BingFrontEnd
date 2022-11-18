<?php

namespace App\Http\Resources\Api\v1;

use App\Http\Resources\Api\v1\User\MerchantResource;
use App\Http\Resources\Api\v1\User\ProfileResource;
use Illuminate\Http\Resources\Json\JsonResource;

class RewardResource extends JsonResource
{

    public function toArray($request)
    {

        $except_arr_resource = $request['except_arr_resource'];
        $response = [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->image,
            'price' => $this->price,
            'points' => $this->points,
            'merchant_name' => optional($this->branch)->name,
        ];
        if (!is_array($except_arr_resource) || !in_array('branch', $except_arr_resource)) $response['branch'] = new BranchResource($this->branch);
        if (!is_array($except_arr_resource) || !in_array('user', $except_arr_resource))
            $response['user'] = new ProfileResource($this->user);
        return $response;
    }
}
