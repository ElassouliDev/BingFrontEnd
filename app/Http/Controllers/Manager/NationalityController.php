<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Nationality;
use App\Models\TransporterType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class NationalityController extends Controller
{
    private $_model;

    public function __construct(Nationality $nationality)
    {
        parent::__construct();
        $this->_model = $nationality;
        $this->middleware('permission:Nationality', ['only' => ['index', 'create', 'edit']]);
        foreach (config('translatable.locales') as $local)
        {
            $this->validationRules["name.$local"] = 'required';
        }
        $this->validationRules["ordered"] = 'required';
    }

    public function index()
    {
        $title = t('Show Nationality');
        if (request()->ajax())
        {
            $transporters = $this->_model->withoutGlobalScope('ordered')->latest();
            $search = request()->get('search', false);
                $transporters = $transporters->when($search, function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                });
            return DataTables::make($transporters)
                ->escapeColumns([])
                ->addColumn('created_at', function ($transporterType){
                    return Carbon::parse($transporterType->created_at)->toDateTimeString();
                })
                ->addColumn('name', function ($transporterType){
                    return $transporterType->name;
                })
                ->addColumn('actions', function ($transporterType) {
                    return $transporterType->action_buttons;
                })
                ->make();
        }
        return view('manager.nationality.index', compact('title'));
    }

    public function create()
    {
        $title = t('Add City');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view('manager.nationality.edit', compact('title', 'validator'));
    }
    public function store(Request $request)
    {
        if (isset($request->nationality_id)) {
            $store = $this->_model->findOrFail($request->nationality_id);
        } else {
            $store = new $this->_model();
        }
        $request->validate($this->validationRules);
        $store->name = $request->name;
        $store->draft = $request->get('draft', 0);
        $store->save();
        if (isset($request->transporter_id)) {
            return redirect()->route('manager.nationality.index')->with('m-class', 'success')->with('message', t('Successfully Updated'));
        } else {
            return redirect()->route('manager.nationality.index')->with('m-class', 'success')->with('message', t('Successfully Created'));
        }
    }


    public function edit($id)
    {
        $title = t('Edit Nationality');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $nationality = $this->_model->findOrFail($id);
        return view('manager.nationality.edit', compact('title', 'nationality', 'validator'));
    }


    public function destroy($id)
    {
        $transporter = $this->_model->findOrFail($id);
//        if($transporterType->joinUs()->count()) return redirect()->back()->with('m-class', 'error')->with('message', t('cannot delete city it has join us'));
        if($transporter->users()->count()) return redirect()->back()->with('m-class', 'error')->with('message', t('cannot delete city it has users'));
        $transporter->delete();
        return redirect()->route('manager.nationality.index')->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }
}
