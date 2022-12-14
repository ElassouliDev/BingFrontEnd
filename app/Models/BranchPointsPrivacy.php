<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BranchPointsPrivacy extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    protected $table = 'branch_points_privacy';

    protected static function boot()
    {
        parent::boot(); //  Change the autogenerated stub
        static::addGlobalScope('orderedBy', function (Builder $builder) {
            $builder->latest('branch_points_privacy.updated_at');
        });
        if (request()->is('api/*')) {
            static::addGlobalScope('notDraft', function (Builder $builder) {
                $builder->where('draft', false);
            });
        }

    }


    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }


//    scopes
    public function scopeCurrentBranch($query, $branch_id)
    {
        return $query->where('branch_id', $branch_id);
    }
}
