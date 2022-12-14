<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Laravel\Passport\HasApiTokens;
use Spatie\Translatable\HasTranslations;


class User extends Authenticatable
{
    use Notifiable, SoftDeletes, HasApiTokens;
    use HasTranslations;

    public $translatable = ['name'];

    const  StoreFileFolder = 'users';
    const type = [
        'CUSTOMER' => 1,  // default
        'EMPLOYEE' => 2,
        'NOT_DETECTED' => 3
    ];

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot(); //  Change the autogenerated stub
        static::addGlobalScope('orderedBy', function (Builder $builder) {
            $builder->orderBy('ordered')->latest('updated_at');
        });

        if (request()->is('api/*')) {
            static::addGlobalScope('notDraft', function (Builder $builder) {
                $builder->notDraft();
            });
//        static::addGlobalScope('active', function (Builder $builder) {
//            $builder->active();
//        });
        }

    }

    protected $hidden = ['password', 'remember_token',];


    //    relations
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }


    public function points()
    {
        return $this->hasMany(UserMerchantPoints::class, 'user_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function wallets()
    {
        return $this->hasMany(Wallet::class);
    }


//    Scopes

    public function scopePhone($query, $param)
    {
        return $query->where('phone', $param);
    }


    public function scopeCurrentMerchant($query, $param)
    {
        return $query->where('merchant_id', $param);
    }

    public function scopeCurrentBranch($query, $param)
    {
        return $query->where('branch_id', $param);
    }

    public function scopeActive($query)
    {
        return $query->where('verified', YES);
    }

    public function scopeNotDraft($query)
    {
        return $query->where('users.draft', false);
    }

    public function scopeClient($query)
    {
        return $query->where('type', self::type['CUSTOMER']);
    }

    public function scopeEmployee($query)
    {
        return $query->where('type', self::type['EMPLOYEE']);
    }


//    attributes
    public function getAccountTypeNameAttribute()
    {
        switch ($this->type) {
            case self::type['CUSTOMER']:
                return api('client');
            case self::type['EMPLOYEE']:
                return api('Employee');
            case self::type['NOT_DETECTED']:
                return api('not detected');
            default:
                return api('unknown status');
        }
    }

    public function getPoint($branch_id)
    {
        return $this->points()->where('branch_id', $branch_id)->first();
    }

    public function getImageAttribute($value)
    {
        return is_null($value) ? defualtImage() : asset($value);
    }

    public function getUserWalletAttribute()
    {
        $admin_charging = Wallet::query()->where('user_id', $this->id)->where('t_type', Wallet::ADMIN_CHARGING)->sum('amount');
        $cancel_order = Wallet::query()->where('user_id', $this->id)->where('t_type', Wallet::CANCEL_ORDER)->sum('amount');
        $new_order = Wallet::query()->where('user_id', $this->id)->where('t_type', Wallet::NEW_ORDER)->sum('amount');
        return ($admin_charging + $cancel_order) - $new_order;
    }

    public function getUnreadNotificationsAttribute()
    {
        return $this->unreadNotifications()->count();
    }

    public function getActionButtonsAttribute()
    {
        $route = 'user';
        if (Auth::guard('manager')->check()) {
            $button = '';
            $button .= '<a href="' . route('manager.' . $route . '.edit', $this->id) . '" class="btn btn-icon btn-danger "><i class="la la-pencil"></i></a> ';
            $button .= '<a href="' . route('manager.' . $route . '.show', $this->id) . '" class="btn btn-icon btn-danger "><i class="la la-eye"></i></a> ';
            $button .= '<button type="button" data-id="' . $this->id . '" data-toggle="modal" data-target="#deleteModel" class="deleteRecord btn btn-icon btn-danger"><i class="la la-trash"></i></button>';
            return $button;
        } elseif (Auth::guard('merchant')->check()) {
            $button = '';
            $button .= '<a href="' . route('restaurant.' . $route . '.edit', $this->id) . '" class="btn btn-icon btn-danger "><i class="la la-pencil"></i></a> ';
            $button .= '<a href="' . route('restaurant.' . $route . '.show', $this->id) . '" class="btn btn-icon btn-danger "><i class="la la-eye"></i></a> ';
            $button .= '<button type="button" data-id="' . $this->id . '" data-toggle="modal" data-target="#deleteModel" class="deleteRecord btn btn-icon btn-danger"><i class="la la-trash"></i></button>';
            return $button;
        } elseif (Auth::guard('branch')->check()) {
            $button = '';
            $button .= '<a href="' . route('branch.' . $route . '.edit', $this->id) . '" class="btn btn-icon btn-danger "><i class="la la-pencil"></i></a> ';
            $button .= '<a href="' . route('branch.' . $route . '.show', $this->id) . '" class="btn btn-icon btn-danger "><i class="la la-eye"></i></a> ';
            $button .= '<button type="button" data-id="' . $this->id . '" data-toggle="modal" data-target="#deleteModel" class="deleteRecord btn btn-icon btn-danger"><i class="la la-trash"></i></button>';
            return $button;
        }

    }

    public function getEmailAttribute($value)
    {
        return ($this->hide_email) ? obfuscate_email($value) : $value;
    }

    public function getPhoneAttribute($value)
    {
        return ($this->hide_mobile) ? hide_mobile($value,3) : $value;
    }


//methods
    public function setLanguage()
    {
        $locale = $this->local ?? config("app.fallback_locale");
        app()->setLocale($locale);
    }


    protected $casts = [
        'email_verified_at' => 'datetime',
        'updated_at' => 'datetime',
        'lat' => 'double',
        'lng' => 'double',
        'type' => 'integer',
        'hide_mobile' => 'bool',
        'hide_email' => 'bool',
        'verified' => 'bool',
    ];

}
