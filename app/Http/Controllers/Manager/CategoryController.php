<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:Restaurants Categories', ['only' => ['index', 'create', 'edit']]);
        foreach (config('translatable.locales') as $local)
        {
            $this->validationRules["name:$local"] = 'required';
        }
        $this->validationRules["tags"] = 'nullable';
        $this->validationRules["ordered"] = 'required';
    }

    public function index()
    {
        $title = t('Show Categories');
        if (request()->ajax())
        {
            $categories = Category::query()->withoutGlobalScope('ordered')->latest();
            $search = request()->get('search', false);
            if(!empty($search) || $search != '') {
                $categories = $categories->whereHas('translations', function ($query) use ($search) {
                    $query->whereIn('locale', config('translatable.locales'))
                        ->where('name', 'like', '%' . $search . '%');
                });
            }
            return DataTables::make($categories)
                ->escapeColumns([])
                ->addColumn('created_at', function ($category){
                    return Carbon::parse($category->created_at)->toDateTimeString();
                })
                ->addColumn('actions', function ($category) {
                    return $category->action_buttons;
                })
                ->make();
        }
        return view('manager.category.index', compact('title'));
    }

    public function create()
    {
        $title = t('Add Category');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view('manager.category.edit', compact('title', 'validator'));
    }

    public function store(Request $request)
    {
        $request->validate($this->validationRules);
        $data = $request->all();
        $data['draft'] = $request->get('draft', 0);
        Category::create($data);
        return redirect()->route('manager.category.index')->with('m-class', 'success')->with('message', t('Successfully Created'));
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $title = t('Edit Category');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $category = Category::query()->findOrFail($id);
        return view('manager.category.edit', compact('title', 'category', 'validator'));
    }

    public function update(Request $request, $id)
    {
        $request->validate($this->validationRules);
        $category = Category::query()->findOrFail($id);
        $data = $request->all();
        $data['draft'] = $request->get('draft', 0);
        $category->update($data);
        return redirect()->route('manager.category.index')->with('m-class', 'success')->with('message', t('Successfully Updated'));

    }

    public function destroy($id)
    {
        $category = Category::query()->findOrFail($id);
        if ($category->branches->count())
        {
            return redirect()->back()->with('m-class', 'error')->with('message', t('cannot delete category it has branches'));
        }
        $category->delete();
        return redirect()->route('manager.category.index')->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }
}
