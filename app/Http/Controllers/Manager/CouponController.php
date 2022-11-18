<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\BranchCoupons;
use App\Models\Coupon;
use App\Models\CouponBranch;
use App\Models\Merchant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class CouponController extends Controller
{
    private $_model;

    public function __construct(Coupon $coupon)
    {
        parent::__construct();
        $this->_model = $coupon;
        $this->middleware('permission:Coupons', ['only' => ['index', 'create', 'edit']]);
        $this->validationRules["code"] = 'required';
        $this->validationRules["amount"] = 'required';
        $this->validationRules["type"] = 'required';
        $this->validationRules["number_users"] = 'required';
        $this->validationRules["number_usage"] = 'required';
        $this->validationRules["expire_at"] = 'required|date_format:Y-m-d H:i:s';
    }

    public function index()
    {
        $title = t('Show Coupons');
        if (request()->ajax()) {
            $search = request()->get('search', false);
            $coupons = $this->_model/*->notDraft()*/->when($search, function ($query) use ($search) {
                $query->where('code', 'like', '%' . $search . '%');
            })->latest();
            return DataTables::make($coupons)
                ->escapeColumns([])
                ->addColumn('expire_at', function ($category) {
                    return Carbon::parse($category->expire_at)->format(DATE_FORMAT);
                })
                ->addColumn('type', function ($category) {
                    return $category->type == 1 ? t('Ratio') : t('Amount');
                })
                ->addColumn('actions', function ($category) {
                    return $category->action_buttons;
                })
                ->make();
        }
        return view('manager.coupon.index', compact('title'));
    }

    public function create()
    {
        $title = t('Add Coupon');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $restaurants = Merchant::get();
        return view('manager.coupon.edit', compact('title', 'validator', 'restaurants'));
    }


    public function edit($id)
    {
        $title = t('Edit Coupon');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $coupon = $this->_model->findOrFail($id);
        $restaurants = Merchant::has('branches')->get();
        $branches = BranchCoupons::query()->where('coupon_id', $id)->get()->pluck('branch_id')->all();
        return view('manager.coupon.edit', compact('title', 'coupon', 'validator', 'restaurants', 'branches'));
    }


    public function store(Request $request)
    {
        if (isset($request->coupon_id)) {
            $store = $this->_model->findOrFail($request->coupon_id);
        } else {
            $store = new $this->_model();
        }
        $request->validate($this->validationRules);
//dd(checkRequestIsWorkingOrNot());
        $store->code = $request->code;
        $store->amount = $request->amount;
        $branchesIds = collect($request->branches);
        if (count($branchesIds) > 0) {
            $store->merchant_id = Branch::findOrFail($branchesIds->first())->merchant_id;
        }
        $store->type = $request->type;
        $store->number_users = $request->number_users;
        $store->number_usage = $request->number_usage;
        $store->expire_at = $request->expire_at;
        $store->draft = $request->get('draft', 0);
        $store->save();
        $store->branches()->sync((array)$request->branches);
//
//        if (count($request->get('branches', []))) {
//            foreach ($request->get('branches', []) as $branch) {
//                CouponBranch::create([
//                    'coupon_id' => $coupon->id,
//                    'branch_id' => $branch,
//                ]);
//            }
//        }


        if (isset($request->coupon_id)) {
            return redirect()->route('manager.coupon.index')->with('m-class', 'success')->with('message', t('Successfully Updated'));
        } else {
            return redirect()->route('manager.coupon.index')->with('m-class', 'success')->with('message', t('Successfully Created'));
        }
    }




    public function destroy($id)
    {
        $coupon = Coupon::query()->findOrFail($id);
        if ($coupon->orders()->count()) {
            return redirect()->back()->with('m-class', 'error')->with('message', t('cannot delete coupon it has orders'));
        }
        $coupon->delete();
        return redirect()->route('manager.coupon.index')->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }
}
