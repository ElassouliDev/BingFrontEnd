<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;

use App\Models\Package;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class PackageController extends Controller
{
    private $_model;

    public function __construct(Package $package)
    {
        parent::__construct();
        $this->_model = $package;
        $this->middleware('permission:Packages', ['only' => ['index', 'create', 'edit']]);
        foreach (config('translatable.locales') as $local) {
            $this->validationRules["name.$local"] = 'required';
        }
    }

    public function index()
    {
        $title = t('Show Packages');
        if (request()->ajax()) {
            $name = request()->get('name', false);
            $status = request()->get('status', false);

            $items = $this->_model->when($name, function ($query) use ($name) {
                $query->where('name', 'like', '%' . $name . '%');
            })
                ->when($status !=null, function ($query) use ($status) {
                    $query->where('draft',$status);
                })
//                ->withoutGlobalScope('notDraft')
                ->latest();

            return DataTables::make($items)
                ->escapeColumns([])
                ->addColumn('name', function ($item) {
                    return $item->name;
                })
//                ->addColumn('usages_number', function ($item) {
//                    return optional($item->merchant)->name;
//                })
                ->addColumn('months', function ($item) {
                    return $item->months;
                })
                ->addColumn('price', function ($item) {
                    return $item->price;
                })
                ->addColumn('delivery_number', function ($item) {
                    return $item->delivery_number;
                })
                ->addColumn('km_limit', function ($item) {
                    return $item->km_limit;
                })
                ->addColumn('draft_name', function ($item) {
                    return $item->draft_name;
                })
                ->addColumn('actions', function ($item) {
                    return $item->action_buttons;
                })
                ->addColumn('created_at', function ($item) {
                    return Carbon::parse($item->created_at)->toDateTimeString();
                })
                ->make();
        }
        return view('manager.package.index', compact('title'));
    }

    public function create()
    {
        $title = t('Add Package');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view('manager.package.edit', compact('title', 'validator'));
    }


    public function edit($id)
    {
        $title = t('Edit Package');
        $package = $this->_model->findOrFail($id);
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view('manager.package.edit', compact('title', 'validator', 'package'));

    }


    public function store(Request $request)
    {
        if (isset($request->package_id)) {
            $store = $this->_model->findOrFail($request->package_id);
        } else {
            $store = new $this->_model();
        }
        $request->validate($this->validationRules);
        $store->name = $request->name;
        $store->months = $request->months_number;
        $store->price = $request->price;
        $store->delivery_number = $request->deliveries_number;
        $store->km_limit = $request->km_limit;
        $store->draft = $request->get('draft', 0);
        if ($request->hasFile('image')) {
            $store->image = $this->uploadImage($request->file('image'), 'packages');
        }
        $store->save();

        if (isset($request->package_id)) {
            return redirect()->route('manager.package.index')->with('m-class', 'success')->with('message', t('Successfully Updated'));
        } else {
            return redirect()->route('manager.package.index')->with('m-class', 'success')->with('message', t('Successfully Created'));
        }
    }


    public function destroy($id)
    {
        $item = $this->_model->with(['users'])->findOrFail($id);
        if ($item->users()->count() > 0) return redirect()->back()->with('message', t('Can not Delete Package, Package Related With Users'))->with('m-class', 'error');
        $item->delete();
        return redirect()->back()->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }


}
