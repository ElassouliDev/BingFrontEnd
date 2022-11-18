<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Merchant;
use App\Models\Provider;
use App\Models\Slider;
use App\Models\SliderImages;
use Carbon\Carbon;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\This;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class SliderController extends Controller
{
    private $_model;

    public function __construct(SliderImages $sliderImages)
    {
        parent::__construct();
        $this->_model = $sliderImages;
        $this->middleware('permission:Sliders', ['only' => ['index', 'create', 'edit', 'destroy']]);

        $this->validationRules["type"] = 'required|in:' . SliderImages::URL_EXTERNAL . ',' . SliderImages::MERCHANT;
        $this->validationRules["image"] = 'required|image';
        $this->validationRules["merchant_id"] = 'nullable|exists:merchants,id';
        $this->validationRules["EXTERNAL_URL"] = 'nullable|url';
    }

    public function index()
    {
        $title = t('Show Sliders');
        if (request()->ajax()) {
            $sliders = $this->_model->withoutGlobalScope('ordered')->latest();
            $search = request()->get('search', false);
            if (!empty($search) || $search != '') {
                $sliders = $sliders->whereHas('provider', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                });
            }
            return DataTables::make($sliders)
                ->escapeColumns([])
                ->addColumn('created_at', function ($slider) {
                    return Carbon::parse($slider->created_at)->toDateTimeString();
                })
                ->addColumn('merchant', function ($slider) {
                    $merchant = $slider->merchant;
                    return isset($merchant) ? $merchant->name : t('no_item');
                })
                ->addColumn('url', function ($slider) {
                    return isset($slider->url) ? $slider->url : t('no_item');
                })
                ->addColumn('type_name', function ($slider) {
                    return $slider->type_name;
                })
                ->addColumn('image', function ($slider) {
                    return '<img src="' . asset($slider->image) . '" width="50" height="50" />';
                })
                ->addColumn('actions', function ($slider) {
                    return $slider->action_buttons;
                })
                ->make();
        }
        return view('manager.slider.index', compact('title'));
    }

    public function create()
    {
        $title = t('Add Slider');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $branches = Merchant::get();
        return view('manager.slider.edit', compact('title', 'validator', 'branches'));
    }

    public function store(Request $request)
    {

        if (isset($request->slider_id)) {
            $this->validationRules["image"] = 'nullable|image';
            $store = $this->_model->findOrFail($request->slider_id);
        } else {
            $store = new $this->_model();
        }
        $request->validate($this->validationRules);
//        dd(checkRequestIsWorkingOrNot());
        if ($request->hasFile('image')) {
            $store->image = $this->uploadImage($request->file('image'), 'slider');
        }
        $store->type = $request->type;
        if ($store->type == SliderImages::MERCHANT) {
            $store->merchant_id = $request->merchant_id;
            $store->url = null;
        } else {
            $store->merchant_id = null;
            $store->url = $request->EXTERNAL_URL;
        }
        $store->save();
        return redirect()->route('manager.slider.index')->with('m-class', 'success')->with('message', t('Successfully Created'));
    }


    public function edit($id)
    {
        $title = t('Edit Slider');
        $this->validationRules["image"] = 'nullable|image';
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $slider = $this->_model->findOrFail($id);
        $branches = Merchant::get();
        return view('manager.slider.edit', compact('title', 'slider', 'validator', 'branches'));
    }


    public function destroy($id)
    {
        $slider = SliderImages::query()->findOrFail($id);
        $slider->delete();
        return redirect()->route('manager.slider.index')->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }
}
