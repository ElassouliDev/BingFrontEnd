<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class OrderStatusTimeLine extends Model
{
    use HasTranslations;
    use SoftDeletes;
    public $translatable = ['key_name', 'details'];


    protected $table = 'order_time_line_status';
    protected $guarded = [];

//    Relations
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
