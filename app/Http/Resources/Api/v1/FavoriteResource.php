<?php

namespace App\Http\Resources\Api\v1;

use App\Http\Resources\Api\v1\User\MerchantResource;
use Illuminate\Http\Resources\Json\JsonResource;

class FavoriteResource extends JsonResource
{

    public function toArray($request)
    {
        $except_arr_resource = $request['except_arr_resource'];

        return new BranchResource($this->branch);
    }
}
