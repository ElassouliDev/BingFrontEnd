<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\City;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class CityController extends Controller
{
    private $_model;

    public function __construct(City $city)
    {
        parent::__construct();
        $this->_model = $city;
        $this->middleware('permission:Cities', ['only' => ['index', 'create', 'edit']]);
        foreach (config('translatable.locales') as $local)
        {
            $this->validationRules["name.$local"] = 'required';
        }
        $this->validationRules["ordered"] = 'required';
    }

    public function index()
    {
        $title = t('Show Cities');
        if (request()->ajax())
        {
            $cities = City::query()->withoutGlobalScope('ordered')->latest();
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
        return view('manager.city.index', compact('title'));
    }

    public function create()
    {
        $title = t('Add City');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view('manager.city.edit', compact('title', 'validator'));
    }

    public function store(Request $request)
    {
        if (isset($request->city_id)) {
            $store = $this->_model->findOrFail($request->city_id);
        } else {
            $store = new $this->_model();
        }
        $request->validate($this->validationRules);
        $store->name = $request->name;
        $store->draft = $request->get('draft', 0);
        $store->save();
        if (isset($request->city_id)) {
            return redirect()->route('manager.city.index')->with('m-class', 'success')->with('message', t('Successfully Updated'));
        } else {
            return redirect()->route('manager.city.index')->with('m-class', 'success')->with('message', t('Successfully Created'));
        }
    }


    public function edit($id)
    {
        $title = t('Edit City');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $city = City::query()->findOrFail($id);
        return view('manager.city.edit', compact('title', 'city', 'validator'));
    }


    public function destroy($id)
    {
        $city = City::query()->findOrFail($id);
//        if($city->joinUs()->count()) return redirect()->back()->with('m-class', 'error')->with('message', t('cannot delete city it has join us'));
        if($city->users()->count()) return redirect()->back()->with('m-class', 'error')->with('message', t('cannot delete city it has users'));
        $city->delete();
        return redirect()->route('manager.city.index')->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }
}
