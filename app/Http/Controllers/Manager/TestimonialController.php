<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class TestimonialController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:Testimonials', ['only' => ['index','create','edit', 'destroy']]);

        foreach (config('translatable.locales') as $local)
        {
            $this->validationRules["name:$local"] = 'required';
            $this->validationRules["content:$local"] = 'required';
        }
        $this->validationRules["ordered"] = 'required';
        $this->validationRules["type"] = 'required';
    }

    public function index()
    {
        $title = t('Show Testimonials');
        if (request()->ajax())
        {
            $testimonials = Testimonial::query()->withoutGlobalScope('ordered')->latest();
            $search = request()->get('search', false);
            if(!empty($search) || $search != '') {
                $testimonials = $testimonials->whereTranslationLike('name', '%' . $search . '%');
            }
            return DataTables::make($testimonials)
                ->escapeColumns([])
                ->addColumn('created_at', function ($category){
                    return Carbon::parse($category->created_at)->toDateTimeString();
                })
                ->addColumn('actions', function ($category) {
                    return $category->action_buttons;
                })
                ->make();
        }
        return view('manager.testimonial.index', compact('title'));
    }

    public function create()
    {
        $title = t('Add Testimonial');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view('manager.testimonial.edit', compact('title', 'validator'));
    }

    public function store(Request $request)
    {
        $request->validate($this->validationRules);
        $data = $request->all();
        Testimonial::create($data);
        return redirect()->route('manager.testimonial.index')->with('m-class', 'success')->with('message', t('Successfully Created'));

    }

    public function show($id)
    {

    }

    public function edit($id)
    {
        $title = t('Add Testimonial');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $testimonial = Testimonial::query()->findOrFail($id);
        return view('manager.testimonial.edit', compact('title', 'validator', 'testimonial'));
    }

    public function update(Request $request, $id)
    {
        $request->validate($this->validationRules);
        $testimonial = Testimonial::query()->findOrFail($id);
        $data = $request->all();
        $testimonial->update($data);
        return redirect()->route('manager.testimonial.index')->with('m-class', 'success')->with('message', t('Successfully Updated'));

    }

    public function destroy($id)
    {
        $testimonial = Testimonial::query()->findOrFail($id);
        $testimonial->delete();
        return redirect()->route('manager.testimonial.index')->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }
}
