<?php

namespace App\Http\Resources\Api\v1\Driver;

use App\Http\Resources\Api\v1\OrderResource;
use App\Http\Resources\Api\v1\OrderStatusTimeLineResource;
use App\Http\Resources\Api\v1\User\ProfileResource;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryResource extends JsonResource
{
    public function toArray($request)
    {

        $except_arr_resource = $request['except_arr_resource'];
        $response = [
            'uuid' => $this->order->uuid,
            'order_id' => $this->order->id,

            'id' => $this->id,
            'status' => $this->status,
            'status_name' => $this->status_name,
            'counter' => $this->counter,
            'time' => Carbon::parse($this->counter)->format(TIME_FORMAT),
            'distance' => $this->distance,
        ];


        if (!is_array($except_arr_resource) || !in_array('status_time_line', $except_arr_resource)) $response['status_time_line'] = OrderStatusTimeLineResource::collection($this->status_time_lines);
        if (!is_array($except_arr_resource) || !in_array('order', $except_arr_resource)) $response['order'] = new OrderResource($this->order);
        if (!is_array($except_arr_resource) || !in_array('driver', $except_arr_resource)) $response['driver'] = new ProfileResource($this->driver);
        return $response;
    }
}
