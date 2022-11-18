<?php

namespace App\Http\Resources\Api\v1;

use App\Http\Resources\Api\v1\User\MerchantResource;
use App\Models\SliderImages;
use Illuminate\Http\Resources\Json\JsonResource;

class SlidersResource extends JsonResource
{
    public function toArray($request)
    {

        $except_arr_resource = $request['except_arr_resource'];

        $response = [
            'id' => $this->id,
            'image' => $this->image,
            'type' => $this->type,
        ];
        if (SliderImages::URL_EXTERNAL == $this->type) $response['value'] = $this->url;
        elseif (SliderImages::MERCHANT == $this->type) $response['merchant'] = new MerchantResource($this->merchant);
//        elseif (SliderImages::ITEM == $this->type) $response['item'] = new ItemResource($this->item);
        return $response;
    }
}
