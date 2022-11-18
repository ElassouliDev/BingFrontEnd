<?php

namespace App\Http\Resources\Api\v1;

use App\Http\Resources\Api\v1\User\MerchantResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{

    public function toArray($request)
    {

        $except_arr_resource = $request['except_arr_resource'];
        $response = [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->image,
            'price' => $this->price,
        ];
        if (!is_array($except_arr_resource) || !in_array('classification', $except_arr_resource)) $response['classification'] = new ClassificationResource($this->classification);
        if (!is_array($except_arr_resource) || !in_array('merchant', $except_arr_resource)) $response['merchant'] = new MerchantResource($this->merchant);
        if (!is_array($except_arr_resource) || !in_array('branch', $except_arr_resource)) $response['branch'] = new BranchResource($this->branch);
        return $response;
    }
}
