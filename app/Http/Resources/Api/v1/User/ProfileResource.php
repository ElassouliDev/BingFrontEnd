<?php

namespace App\Http\Resources\Api\v1\User;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    public function toArray($request)
    {
        $except_arr_resource = $request['except_arr_resource'];
        $res = [];
        $response = [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->image,
            'phone' => $this->phone,
            'email' => $this->email,
            'hide_email' => $this->hide_email,
            'hide_mobile' => $this->hide_mobile,
            'type' => $this->type,
            'type_name' => $this->account_type_name,
            'verified' => $this->verified,
            'gender' => $this->gender,
            'gender_name' => gender($this->gender),
            'code' => $this->generatedCode,
            'lat' => $this->lat,
            'lng' => $this->lng,
//            'city' => new CityResource($this->city),
//            'dob' => $this->dob,
//            'rate' => $res,
            'local' => $this->local,
            'notification' => (bool)$this->notification,
            'unread_notifications' => (int)$this->unread_notifications,
            'branch_id' => $this->branch_id,
            'access_token' => $this->access_token,
        ];
        return $response;
    }
}
