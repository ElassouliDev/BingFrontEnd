<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\City;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class BankController extends Controller
{
    private $_model;

    public function __construct(Bank $bank)
    {
        parent::__construct();
        $this->_model = $bank;
        $this->middleware('permission:Banks', ['only' => ['index', 'create', 'edit']]);
        foreach (config('translatable.locales') as $local)
        {
            $this->validationRules["name.$local"] = 'required';
        }
        $this->validationRules["ordered"] = 'required';
    }

    public function index()
    {
        $title = t('Show Banks');
        if (request()->ajax())
        {
            $cities = $this->_model->withoutGlobalScope('ordered')->latest();
            $search = request()->get('search', false);
            $cities = $cities->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });
            return DataTables::make($cities)
                ->escapeColumns([])
                ->addColumn('created_at', function ($city){
                    return Carbon::parse($city->created_at)->toDateTimeString();
                })
                ->addColumn('name', function ($city){
                    return $city->name;
                })
                ->addColumn('actions', function ($city) {
                    return $city->action_buttons;
                })
                ->make();
        }
        return view('manager.bank.index', compact('title'));
    }

    public function create()
    {
        $title = t('Add Bank');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view('manager.bank.edit', compact('title', 'validator'));
    }


    public function edit($id)
    {
        $title = t('Edit Bank');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $bank = Bank::query()->findOrFail($id);
        return view('manager.bank.edit', compact('title', 'bank', 'validator'));
    }



    public function store(Request $request)
    {
        if (isset($request->bank_id)) {
            $store = $this->_model->findOrFail($request->bank_id);
        } else {
            $store = new $this->_model();
        }
        $request->validate($this->validationRules);
        $store->name = $request->name;
        $store->draft = $request->get('draft', 0);
        $store->save();
        if (isset($request->bank_id)) {
            return redirect()->route('manager.bank.index')->with('m-class', 'success')->with('message', t('Successfully Updated'));
        } else {
            return redirect()->route('manager.bank.index')->with('m-class', 'success')->with('message', t('Successfully Created'));
        }
    }



    public function destroy($id)
    {
        $bank = $this->_model->findOrFail($id);
        if($bank->users()->count())
        {
            return redirect()->back()->with('m-class', 'error')->with('message', t('cannot delete bank it has users'));
        }
        if($bank->merchants()->count())
        {
            return redirect()->back()->with('m-class', 'error')->with('message', t('cannot delete bank it has restaurants'));
        }
        $bank->delete();
        return redirect()->route('manager.bank.index')->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }
}
