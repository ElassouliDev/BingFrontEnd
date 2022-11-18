<?php

namespace App\Http\Resources\Api\v1;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class BranchHoursResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'day' => (int) $this->day,
            'name' => $this->day_name,
            'from' => Carbon::parse($this->from)->format('h:i A'),
            'to' => Carbon::parse($this->to)->format('h:i A'),
            'selected' =>  $this->selected,
        ];
    }
}
