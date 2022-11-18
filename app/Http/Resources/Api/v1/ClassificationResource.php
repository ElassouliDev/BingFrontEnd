<?php

namespace App\Http\Resources\Api\v1;

use App\Http\Resources\Api\v1\User\MerchantResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassificationResource extends JsonResource
{

    public function toArray($request)
    {
        $except_arr_resource = $request['except_arr_resource'];
        $response = [
            'id' => $this->id,
            'name' => $this->name,
        ];
        if (!is_array($except_arr_resource) || !in_array('merchant', $except_arr_resource)) {
            $response['merchant'] = new MerchantResource($this->merchant);
        }


        if (!is_array($except_arr_resource) || !in_array('items', $except_arr_resource)) {
            $response['items'] =ItemResource::collection($this->items);
        }

        return $response;

    }
}
