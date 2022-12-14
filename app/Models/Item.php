<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Translatable\HasTranslations;

class Item extends Model
{
    use SoftDeletes;
    use HasTranslations;

    public $translatable = ['name', 'description'];
    protected $guarded = [];


    protected static function boot()
    {
        parent::boot(); //  Change the autogenerated stub
        static::addGlobalScope('orderedBy', function (Builder $builder) {
            $builder->orderBy('items.ordered')->latest('updated_at');
        });

        if (request()->is('api/*')) {
            static::addGlobalScope('notDraft', function (Builder $builder) {
                $builder->notDraft();
            });
        }

    }


    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'merchant_id', 'id');
    }

    public function classification()
    {
        return $this->belongsTo(Classification::class, 'classification_id', 'id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    public function getImageAttribute($value)
    {
        return is_null($value) ? asset('meal_image.png') : asset($value);
    }

    public function getActionButtonsAttribute()
    {
        if (Auth::guard('manager')->check()) {
            $button = '';
            $button .= '<a href="' . route('manager.meal.edit', $this->id) . '" class="btn btn-icon btn-danger "><i class="la la-pencil"></i></a> ';
            $button .= '<button type="button" data-id="' . $this->id . '" data-toggle="modal" data-target="#deleteModel" class="deleteRecord btn btn-icon btn-danger"><i class="la la-trash"></i></button>';
            return $button;
        } elseif (Auth::guard('merchant')->check()) {
            $button = '';
            $button .= '<a href="' . route('restaurant.meal.edit', $this->id) . '" class="btn btn-icon btn-danger "><i class="la la-pencil"></i></a> ';
            $button .= '<button type="button" data-id="' . $this->id . '" data-toggle="modal" data-target="#deleteModel" class="deleteRecord btn btn-icon btn-danger"><i class="la la-trash"></i></button>';
            return $button;
        } elseif (Auth::guard('branch')->check()) {
            $button = '';
            $button .= '<a href="' . route('branch.meal.edit', $this->id) . '" class="btn btn-icon btn-danger "><i class="la la-pencil"></i></a> ';
            $button .= '<button type="button" data-id="' . $this->id . '" data-toggle="modal" data-target="#deleteModel" class="deleteRecord btn btn-icon btn-danger"><i class="la la-trash"></i></button>';
            return $button;
        }
    }


//    scopes

    public function scopeNotDraft($query)
    {
        return $query->where('draft', false);
    }

    public function scopeHasDiscount($query)
    {
        return $query->where('has_discount', true);
    }


    public function scopeCurrentBranch($query, $branch_id)
    {
        return $query->where('branch_id',$branch_id);
    }

    protected $casts = [
        'has_discount' => 'boolean',
    ];
}
