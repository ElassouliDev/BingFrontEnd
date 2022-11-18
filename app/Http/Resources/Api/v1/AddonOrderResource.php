<?php

namespace App\Http\Resources\Api\v1;

use App\Models\ItemAddon;
use Illuminate\Http\Resources\Json\JsonResource;

class AddonOrderResource extends JsonResource
{

    public function toArray($request)
    {
        $except_arr_resource = $request['except_arr_resource'];

        return [
            'addon' => new AddonResource($this->item_addon),
            'quantity' => $this->quantity,
            'total_addon_cost' => $this->amount,
        ];
    }
}
