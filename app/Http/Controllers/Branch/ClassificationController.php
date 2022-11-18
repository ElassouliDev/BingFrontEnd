<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Classification;
use App\Models\Merchant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class ClassificationController extends Controller
{
    private $_model;

    public function __construct(Classification $classification)
    {
        parent::__construct();
        $this->_model = $classification;
        $this->middleware('permission:Meals Categories', ['only' => ['index', 'create', 'edit']]);
        foreach (config('translatable.locales') as $local) {
            $this->validationRules["name.$local"] = 'required';
        }
        $this->validationRules["ordered"] = 'required';
    }

    public function index()
    {
        $title = t('Show Classifications');
        if (request()->ajax()) {
            $search = request()->get('search', false);
            $name = request()->get('name', false);
            $categories = $this->_model
                ->where('branch_id', user('branch')->id)
                ->when($search, function ($query) use ($search) {
                    $query->where('name->' . lang(), 'like', '%' . $search . '%');
                })
                ->when($name, function ($query) use ($name) {
                    $query->where('name->' . lang(), 'like', '%' . $name . '%');
                });
            return DataTables::make($categories)
                ->escapeColumns([])
                ->addColumn('name', function ($category) {
                    return $category->name;
                })
                ->addColumn('merchant', function ($category) {
                    return optional($category->merchant)->name;
                })
                ->addColumn('meals_count', function ($category) {
                    return $category->items->count();
                })
                ->addColumn('branch', function ($category) {
                    return optional($category->branch)->name;
//                    return implode(optional($category->branches)->pluck('name')->all(), ',');
                })
                ->addColumn('created_at', function ($category) {
                    return Carbon::parse($category->created_at)->toDateTimeString();
                })
                ->addColumn('actions', function ($category) {
                    return $category->action_buttons;
                })
                ->make();
        }
        return view('branch.classification.index', compact('title'));
    }

    public function create()
    {
        $title = t('Add Classification');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view('branch.classification.edit', compact('title', 'validator'));
    }

    public function edit($id)
    {
        $title = t('Add Classification');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $classification = Classification::with(['branch'])->findOrFail($id);
        return view('branch.classification.edit', compact('title', 'validator', 'classification'));
    }

    public function store(Request $request)
    {
        if (isset($request->classification_id)) {
            $store = $this->_model->findOrFail($request->classification_id);
        } else {
            $store = new $this->_model();
        }
        $request->validate($this->validationRules);
        $store->name = $request->name;
        $store->merchant_id = user('branch')->merchant_id;
        $store->branch_id = user('branch')->id;
        $store->draft = $request->get('draft', 0);
        $store->save();
        if (isset($request->classification_id)) {
            return redirect()->route('branch.classification.index')->with('m-class', 'success')->with('message', t('Successfully Updated'));
        } else {
            return redirect()->route('branch.classification.index')->with('m-class', 'success')->with('message', t('Successfully Created'));

        }
    }

    public function destroy($id)
    {
        $classification = $this->_model::currentBranch(user('branch')->id)->findOrFail($id);
        if ($classification->items()->count()) {
            return redirect()->back()->with('m-class', 'error')->with('message', t('cannot delete classification it has meals'));
        }
        $classification->delete();
        return redirect()->route('branch.classification.index')->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }

    public function classifications(Request $request)
    {
        $classifications = $this->_model->notDraft()
            ->whereHas('merchant', function ($query) use ($request) {
                $query->where('id', user('merchant')->id);
            })->whereHas('branch', function ($query) use ($request) {
                $query->where('id', $request->branch_id);
            })->get();
        $html = '<option value="" disabled selected>' . t('Select Classification') . '</option>';
        foreach ($classifications as $classification) {
            $html .= '<option value="' . $classification->id . '">' . $classification->name . '</option>';
        }
        return response()->json(['html' => $html]);
    }

    public function branchClassifications(Request $request)
    {
        $classifications = $this->_model->notDraft()->where('merchant_id', user('merchant')->id)
            ->where('branch_id', $request->id)->get();
        $html = '<option value="" disabled selected>' . t('Select Classification') . '</option>';
        foreach ($classifications as $classification) {
            $html .= '<option value="' . $classification->id . '">' . $classification->name . '</option>';
        }
        return response()->json(['html' => $html]);
    }
}
