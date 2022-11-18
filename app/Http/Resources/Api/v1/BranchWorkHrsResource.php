<?php

namespace App\Http\Resources\Api\v1;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class BranchWorkHrsResource extends JsonResource
{
    public function toArray($request)
    {
        $except_arr_resource = $request['except_arr_resource'];
        $response = [
            'id' => $this->id,
            'day' => days($this->day),
            'from' => Carbon::parse($this->from)->format(TIME_FORMAT_WITHOUT_SECONDS),
            'to' => Carbon::parse($this->to)->format(TIME_FORMAT_WITHOUT_SECONDS),
        ];
        return $response;
    }
}
