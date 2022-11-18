<?php

namespace App\Http\Resources\Api\v1;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class BranchPointsPrivacyResource extends JsonResource
{
    public function toArray($request)
    {
        $except_arr_resource = $request['except_arr_resource'];
        $response = [
            'id' => $this->id,
            'branch_id' => $this->branch_id,
            'new_order' => $this->new_order,
            'when_merchant_late' => $this->when_merchant_late,
            'rate_order' => $this->rate_order,
            'ready_01' => $this->ready_01,
            'ready_04' => $this->ready_04,
            'ready_plus_04' => $this->ready_plus_04,
        ];
        return $response;
    }
}
