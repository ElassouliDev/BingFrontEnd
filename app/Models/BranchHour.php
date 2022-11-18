<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BranchHour extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    protected $appends = [
        'day_name', 'selected'
    ];

    protected static function boot()
    {
        parent::boot(); //  Change the autogenerated stub
        static::addGlobalScope('orderedBy', function (Builder $builder) {
            $builder->orderBy('ordered');
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

    public function getDayNameAttribute()
    {
        return t(days($this->day));
    }

    public function getSelectedAttribute()
    {
        $today = Carbon::now();
        $day = e_days($this->day);
        return (boolean)$today->{'is' . $day}();
    }


//    scopes
    public function scopeCurrentBranch($query, $branch_id)
    {
        return $query->where('branch_id', $branch_id);
    }
}