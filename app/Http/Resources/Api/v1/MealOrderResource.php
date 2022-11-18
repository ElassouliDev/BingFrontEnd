<?php

namespace App\Http\Resources\Api\v1;

use Illuminate\Http\Resources\Json\JsonResource;

class MealOrderResource extends JsonResource
{

    public function toArray($request)
    {
        $except_arr_resource = $request['except_arr_resource'];
        $res = [
            'id' => $this->item->id,
            'branch_id' => optional($this->item->branch)->id,
            'name' => $this->item->name,
            'description' => $this->item->description,
            'image' => $this->item->image,
            'price' => $this->amount,
            'quantity' => $this->quantity,
        ];
        if (!is_array($except_arr_resource) || !in_array('classification', $except_arr_resource)) $response['classification'] = new ClassificationResource($this->item->classification);
        return $res;
    }
}
