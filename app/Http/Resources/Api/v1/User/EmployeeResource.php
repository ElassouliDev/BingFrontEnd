<?php

namespace App\Http\Resources\Api\v1\User;

use App\Http\Resources\Api\v1\BranchResource;
use App\Http\Resources\Api\v1\CityResource;
use App\Http\Resources\Api\v1\BranchWorkHrsResource;
use App\Models\ClientDriverRate;
use App\Models\DriverClientRate;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    public function toArray($request)
    {
        $except_arr_resource = $request['except_arr_resource'];
        $res = [];
        $response = [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'image' => $this->image,
            'email' => $this->email,
            'verified' => (bool)$this->verified,
            'gender' => $this->gender,
            'gender_name' => gender($this->gender),
            'lat' => $this->lat,
            'lng' => $this->lng,
            'city' => new CityResource($this->city),
            'rate' => $res,
            'local' => $this->local,
            'notification' => (bool)$this->notification,
            'unread_notifications' => (int)$this->unread_notifications,
            'access_token' => $this->access_token,
        ];

        if (!is_array($except_arr_resource) || !in_array('branch', $except_arr_resource))
            $response['branch'] = new BranchResource($this->branch);
        return $response;
    }
}
