<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class PageController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:Pages', ['only' => ['index', 'edit', 'update']]);

        foreach (config('translatable.locales') as $local)
        {
            $this->validationRules["name:$local"] = ['required'];
            $this->validationRules["content:$local"] = ['required'];
            $this->validationMessages["name:$local.required"] = t('Page Name Is Required');
            $this->validationMessages["content:$local.required"] = t('Page Content Is Required');
        }
    }

    public function index()
    {
        if (request()->ajax())
        {
            $pages = Page::query()->latest();
            $search = request()->get('search', false);
            if(!empty($search) || $search != '') {
                $pages = $pages->whereHas('translations', function ($query) use ($search) {
                    $query->whereIn('locale', config('translatable.locales'))
                        ->where('name', 'like', '%' . $search . '%');
                });
            }
            return DataTables::make($pages)
                ->escapeColumns([])
                ->addColumn('created_at', function ($city){
                    return Carbon::parse($city->created_at)->toDateString();
                })
                ->addColumn('actions', function ($city) {
                    return $city->action_buttons;
                })
                ->make();
        }
        $title = t('List Of Pages');
        return view('manager.page.index', compact('title'));
    }

    public function edit($id)
    {
        $page = Page::query()->findOrFail($id);
        $this->validationRules['image'] = 'nullable|image';
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $title = t('Edit Page');
        return view('manager.page.edit', compact('title', 'page', 'validator'));
    }

    public function update(Request $request, $id)
    {
        $page = Page::query()->findOrFail($id);
//        $this->validationRules['image'] = 'nullable|image';
        $request->validate($this->validationRules);

        $data = $request->all();
//        if ($request->hasFile('image'))
//        {
//            $data['image'] = $this->uploadImage($request->file('image'), 'pages');
//        }
        $page->update($data);
        return redirect(route('manager.page.index'))->with('message', t('Successfully Updated'));
    }
}
