<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RateOrder extends Model
{

    protected $guarded = [];
    protected $table = 'rate_order';

    protected static function boot()
    {
        parent::boot(); //  Change the autogenerated stub
        static::addGlobalScope('orderedBy', function (Builder $builder) {
            $builder->latest('updated_at');
        });

        if (request()->is('api/*')) {
            static::addGlobalScope('notDraft', function (Builder $builder) {
                $builder->where('draft', false);
            });
        }

    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }


    public function getActionButtonsAttribute()
    {
        $button = '';
        $button .= '<a href="' . route('manager.bank.edit', $this->id) . '" class="btn btn-icon btn-danger "><i class="la la-pencil"></i></a> ';
        $button .= '<button type="button" data-id="' . $this->id . '" data-toggle="modal" data-target="#deleteModel" class="deleteRecord btn btn-icon btn-danger"><i class="la la-trash"></i></button>';
        return $button;
    }
}