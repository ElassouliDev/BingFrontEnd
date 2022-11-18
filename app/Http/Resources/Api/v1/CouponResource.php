<?php

namespace App\Http\Resources\Api\v1;

use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
{

    public function toArray($request)
    {
        $except_arr_resource = $request['except_arr_resource'];

        return [
            'id' => $this->id,
            'code' => $this->code,
            'amount' => $this->amount,
            'type' => (int) $this->type,
            'type_name' => $this->type_name,
        ];
    }
}
