<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\AgentTapFile;
use App\Models\AgentTapFileJoin;
use App\Models\Bank;
use App\Models\City;
use App\Models\ContactUs;
use App\Models\JoinUs;
use App\Models\Merchant;
use App\Models\MerchantType;
use App\Models\User;
use App\Rules\EmailRule;
use App\Rules\IntroMobile;
use App\Rules\StartWith;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class JoinUsController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:Join Us', ['only' => ['index', 'create', 'edit']]);
        foreach (config('translatable.locales') as $local) {
            $this->validationRules["merchant_name.$local"] = 'required';
        }
        $this->validationRules["phone"] = ['required', 'min:13', 'max:13', new StartWith('+966'), new IntroMobile(), 'unique:merchants,phone,{$id},id,deleted_at,NULL'];
        $this->validationRules["email"] = ['required', 'unique:merchants,email,{$id},id,deleted_at,NULL', new EmailRule()];
        $this->validationRules["merchant_type_id"] = 'required|exists:merchant_types,id';
        $this->validationRules["image"] = 'nullable|image';
        $this->validationRules["lat"] = 'nullable';
        $this->validationRules["lng"] = 'nullable';
        $this->validationRules["password"] = 'nullable|min:6';
        $this->validationRules['id_no'] = 'required|numeric|digits:10';
        $this->validationRules['id_file'] = 'nullable';
        $this->validationRules['comm_registration_no'] = 'required';
        $this->validationRules['comm_registration_file'] = 'nullable';
        $this->validationRules['bank_id'] = 'required|exists:banks,id';
        $this->validationRules['city_id'] = 'required|exists:cities,id';
        $this->validationRules['i_ban'] = ['required', new StartWith('SA'), 'min:24', 'max:24'];

    }

    public function index()
    {
        if (request()->ajax()) {
            $contacts = JoinUs::query()->latest();
            $source = request()->get('source', false);
            $city_id = request()->get('city_id', false);
            $merchant_type_id = request()->get('merchant_type_id', false);
            $date = request()->get('date', false);
            $name = request()->get('name', false);
            $mobile = request()->get('mobile', false);

            $contacts = $contacts->when($source, function ($query) use ($source) {
                $query->where('source', $source);
            })
                ->when($city_id, function ($query) use ($city_id) {
                    $query->where('city_id', $city_id);
                })
                ->when($date, function ($query) use ($date) {
                    $query->whereDate('created_at', $date);
                })
                ->when($merchant_type_id, function ($query) use ($merchant_type_id) {
                    $query->where('merchant_type_id', $merchant_type_id);
                })
                ->when($name, function ($query) use ($name) {
                    $query->where('owner_name', 'like', '%' . $name . '%')
                        ->orWhere('merchant_name', 'like', '%' . $name . '%');
                })->when($mobile, function ($query) use ($mobile) {
                    $query->where('phone', 'like', '%' . $mobile . '%');
                });

            return DataTables::make($contacts)
                ->escapeColumns([])
                ->addColumn('merchant_name', function ($row) {
                    return $row->merchant_name;
                })
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('source_name', function ($row) {
                    return $row->source_name;
                })
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateTimeString();
                })
                ->addColumn('city', function ($row) {
                    return optional($row->city)->name;
                })
                ->addColumn('merchant_type', function ($row) {
                    return optional($row->merchant_type)->name;
                })
                ->addColumn('actions', function ($row) {
                    return $row->action_buttons;
                })
                ->make();
        }
        $title = t('Show Join Us List');
        $cities = City::query()->get();
        $merchant_types = MerchantType::query()->get();
        return view('manager.join_us.index', compact('title', 'cities', 'merchant_types'));
    }

    public function show($id)
    {
        $title = t('Add Restaurant');
        $merchant = JoinUs::query()->findOrFail($id);
        $cities = City::query()->get();
        $merchant_types = MerchantType::query()->get();
        $banks = Bank::query()->get();
        $citites = City::query()->get();
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view('manager.join_us.edit', compact('merchant', 'merchant_types', 'cities', 'title', 'banks', 'validator', 'citites'));
    }

    public function store(Request $request)
    {
        $request->validate($this->validationRules);
        $join_us = JoinUs::findOrFail($request->join_us_id);
//        dd(checkRequestIsWorkingOrNot());
        $store = new Merchant();
        $store->owner_name = $request->owner_name;
        $store->name = $request->merchant_name;
        $store->merchant_type_id = $request->merchant_type_id;
        $store->phone = $request->phone;
        $store->email = $request->email;
        $store->password = bcrypt($request->get('password', 123456));

        $active = $request->get('active', 0);
        $store->status = ($active) ? Merchant::ACTIVE : Merchant::NOT_ACTIVE;
        $store->draft = $request->get('draft', 0);
        $store->id_no = $request->id_no;
//        send notification to restaurant if accepted
        $store->accepted = true;// $request->get('accepted', 0);
        $store->bank_id = $request->bank_id;
        $store->city_id = $request->city_id;
        $store->i_ban = $request->i_ban;
        $store->swift_code = $request->swift_code;
        $store->comm_registration_no = $request->comm_registration_no;

        if ($request->hasFile('image')) {
            $store->image = $this->uploadImage($request->file('image'), 'restaurants');
        }else{
            $store->image = $join_us->image;
        }
        if ($request->hasFile('id_file')) {
            $store->id_file = $this->uploadImage($request->file('id_file'), 'restaurants');
        }else{
            $store->id_file = $join_us->id_file;
        }
        if ($request->hasFile('comm_registration_file')) {
            $store->comm_registration_file = $this->uploadImage($request->file('comm_registration_file'), 'restaurants');
        }else{
            $store->comm_registration_file = $join_us->comm_registration_file;
        }
        $store->save();
//        if ($request->hasFile('id_file')) {
//            $this->uploadAgentFileFromRequest($store, $store->id_file, "identity_document");
//        }
//        if ($request->hasFile('comm_registration_file')) {
//            $this->uploadAgentFileFromRequest($store, $store->comm_registration_file, 'tax_document_user_upload');
//        }


        if ($request->has('join_us_id')) {
            JoinUs::query()->where('id', $request->get('join_us_id'))->delete();
        }

        return redirect()->route('manager.restaurant.index')->with('m-class', 'success')->with('message', t('Successfully Created'));
    }

    public function destroy($id)
    {
        $join = JoinUs::query()->findOrFail($id);
        $join->delete();
        return redirect()->back()->with('message', t('Successfully Deleted'))->with('m-class', 'success');
    }

}
