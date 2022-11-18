<?php

namespace App\Http\Resources\Api\v1;

use Illuminate\Http\Resources\Json\JsonResource;

class PriceResource extends JsonResource
{

    public function toArray($request)
    {
        $except_arr_resource = $request['except_arr_resource'];

        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => (double)$this->price,
            'is_default' => (boolean)$this->isDefault,
        ];
    }
}
