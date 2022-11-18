<?php

namespace App\Http\Resources\Api\v1;

use App\Http\Resources\Api\v1\User\ProfileResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{

    public function toArray($request)
    {
        $except_arr_resource = $request['except_arr_resource'];

        $response = [
            'id' => $this->id,
            'isRated' => $this->isRated,
            'uuid' => !is_null($this->uuid) ? '#' . $this->uuid : null,
            'pick_up_time' => lang() == 'ar' ? arabic_date($this->pick_up_time) : english_date($this->pick_up_time),
            'created_at' => lang() == 'ar' ? arabic_date($this->created_at) : english_date($this->created_at),
            'time_needed' => $this->time_needed,
            'pick_up_items' => (int)$this->order_items()->sum('quantity'),
            'status' => $this->status,
            'status_name' => $this->status_name,
            'note' => $this->note,
            'total_cost' => $this->total_cost,
            'meals_cost' => $this->meals_cost,
        ];

        if (!is_array($except_arr_resource) || !in_array('status_time_line', $except_arr_resource)) $response['status_time_line'] = OrderStatusTimeLineResource::collection($this->status_time_lines);
        if (!is_array($except_arr_resource) || !in_array('branch', $except_arr_resource)) $response['branch'] = new BranchResource($this->branch);
        if (!is_array($except_arr_resource) || !in_array('user', $except_arr_resource)) $response['user'] = new ProfileResource($this->user);
        if (!is_array($except_arr_resource) || !in_array('meals', $except_arr_resource)) $response['meals'] = MealOrderResource::collection($this->order_items);
        return $response;
    }
}
