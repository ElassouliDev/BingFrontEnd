<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wallet extends Model
{
    use SoftDeletes;
    //transaction type (1 admin charging | 2 cancel order | 3 new order)

    public const ADMIN_CHARGING = 1;
    public const CANCEL_ORDER = 2;
    public const NEW_ORDER = 3;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getTypeName($order_uuid = NULL)
    {
        // if($order_uuid != null)dd($order_uuid);
        switch ($this->t_type) {
            case self::CANCEL_ORDER:
                return trans('manager.Cancel Order',[
                    'uuid' => $order_uuid
                ]);
            case self::NEW_ORDER:
                return trans('manager.New Order',[
                    'uuid' => $order_uuid
                ]);
            default:
                return trans('manager.Charge Balance');
        }
    }

    public function getActionButtonsAttribute()
    {
        $button = '';
        $button .= '<button type="button" data-id="' . $this->id . '" data-toggle="modal" data-target="#deleteModel" class="deleteRecord btn btn-icon btn-danger"><i class="la la-trash"></i></button>';
        return $button;
    }


    protected $casts = [
        't_type' => 'integer',
    ];

}
