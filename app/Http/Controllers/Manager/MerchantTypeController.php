<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\MerchantType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class MerchantTypeController extends Controller
{
    private $_model;

    public function __construct(MerchantType $merchantType)
    {
        parent::__construct();
        $this->_model = $merchantType;
        $this->middleware('permission:Merchant Types', ['only' => ['index', 'create', 'edit']]);
        foreach (config('translatable.locales') as $local) {
            $this->validationRules["name.$local"] = 'required';
        }
        $this->validationRules["image"] = 'nullable|image';
        $this->validationRules["ordered"] = 'required';
    }

    public function index()
    {
        $title = t('Show Merchant Types');
        if (request()->ajax()) {
            $merchants = $this->_model->with(['merchants'])->latest();
            $search = request()->get('search', false);
            $merchants = $merchants->when($search, function ($query) use ($search) {
                $query->where('name->' . lang(), 'like', '%' . $search . '%');
            });

            return DataTables::make($merchants)
                ->escapeColumns([])
                ->addColumn('name', function ($merchant) {
                    return $merchant->name;
                })
                ->addColumn('merchant_number', function ($merchant) {
                    return $merchant->merchants()->count();
                })
                ->addColumn('created_at', function ($merchant) {
                    return Carbon::parse($merchant->created_at)->toDateTimeString();
                })
                ->addColumn('image', function ($merchant){
                    return '<img src="'.asset($merchant->image).'" width="100" />';
                })
                ->addColumn('actions', function ($merchant) {
                    return $merchant->action_buttons;
                })
                ->make();
        }
        return view('manager.merchant_type.index', compact('title'));
    }

    public function create()
    {
        $title = t('Add Merchant Types');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view('manager.merchant_type.edit', compact('title', 'validator'));
    }


    public function edit($id)
    {
        $title = t('Edit Merchant Types');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $merchant_type = $this->_model->findOrFail($id);
        return view('manager.merchant_type.edit', compact('title', 'merchant_type', 'validator'));
    }


    public function store(Request $request)
    {
        $request->validate($this->validationRules);

        if (isset($request->merchant_type_id)) {
            $store = $this->_model->findOrFail($request->merchant_type_id);
        } else {
            $store = new $this->_model();
        }

        $store->name = $request->name;
        $store->ordered = $request->ordered;
        if ($request->hasFile('image')) {
            $store->image = $this->uploadImage($request->file('image'), 'merchant_types');
        }
        $store->draft = $request->get('draft', 0);
        $store->save();

        if (isset($request->merchant_type_id)) {
            return redirect()->route('manager.merchant_type.index')->with('m-class', 'success')->with('message', t('Successfully Updated'));
        } else {
            return redirect()->route('manager.merchant_type.index')->with('m-class', 'success')->with('message', t('Successfully Created'));
        }


    }


    public function destroy($id)
    {
        $merchant = $this->_model->findOrFail($id);
        if ($merchant->merchants()->count()) {
            return redirect()->back()->with('m-class', 'error')->with('message', t('cannot delete merchant type it has restaurants'));
        }
        if ($merchant->joinUs()->count()) {
            return redirect()->back()->with('m-class', 'error')->with('message', t('cannot delete merchant type it has join us requests'));
        }
        $merchant->delete();
        return redirect()->route('manager.merchant_type.index')->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }
}
