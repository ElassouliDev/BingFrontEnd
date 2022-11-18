<?php

namespace App\Http\Resources\Api\v1\User;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            't_type' => $this->t_type,
            'type_name' => $this->getTypeName(optional($this->order)->uuid),
            'amount' => $this->amount,
            'date' => Carbon::parse($this->updated_at)->format('m/d/Y'),
            'branch' => optional(optional($this->order)->branch)->name,
            'city' => optional(optional(optional($this->order)->branch)->city)->name,
        ];
    }
}
