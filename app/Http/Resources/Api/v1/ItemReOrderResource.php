<?php

namespace App\Http\Resources\Api\v1;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemReOrderResource extends JsonResource
{
    public function toArray($request)
    {
        $except_arr_resource = $request['except_arr_resource'];

        $response = [
            'id' => $this->item->id,
            'branch_id' => optional(optional($this->item)->branch)->id,
            'name' => $this->item->name,
            'description' => $this->description,
            'calories' => (float)$this->calories,
            'image' => $this->item->image,
            'price_category' => optional(optional($this->item->prices()->first())->option_category)->name,
        ];
        if (!is_array($except_arr_resource) || !in_array('classification', $except_arr_resource)) {
            $response['classification'] = new ClassificationResource($this->item->classification);
        }
        if (!is_array($except_arr_resource) || !in_array('price', $except_arr_resource)) {
            $response['price'] = new PriceResource($this->item_price()->notDraft()->first());
        }
        if (!is_array($except_arr_resource) || !in_array('addons', $except_arr_resource)) {
            $response['addons'] = AddonResource::collection($this->order_item_addons()->get());

        }
        return $response;
    }
}
