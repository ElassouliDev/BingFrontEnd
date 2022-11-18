<?php

namespace App\Http\Resources\Api\v1;

use App\Http\Resources\Api\v1\User\MerchantResource;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
{

    public function toArray($request)
    {
        $from = Carbon::parse($this->from);
        $to = Carbon::parse($this->to);

        $day_from = (lang() == 'ar') ? arabicDate($from->englishDayOfWeek) : $from->englishDayOfWeek;
        $time_formatted_from = $from->format(TIME_FORMAT_WITHOUT_SECONDS);

        $day_to = (lang() == 'ar') ? arabicDate($to->englishDayOfWeek) : $to->englishDayOfWeek;
        $time_formatted_to = $to->format(TIME_FORMAT_WITHOUT_SECONDS);
        $except_arr_resource = $request['except_arr_resource'];
        $response = [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->image,
//            'price' => $this->price,
            'day' => $day_from . ' - ' . $day_to,
            'time' => $time_formatted_from . ' - ' . $time_formatted_to,
        ];
        if (!is_array($except_arr_resource) || !in_array('branch', $except_arr_resource)) $response['branch'] = new BranchResource($this->branch);
        return $response;
    }
}
