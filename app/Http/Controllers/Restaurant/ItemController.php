<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Classification;
use App\Models\Item;
use App\Models\ItemAddon;
use App\Models\ItemPrice;
use App\Models\Merchant;
use App\Models\OptionCategory;
use App\Models\OrderItem;
use App\Models\OrderItemAddon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class ItemController extends Controller
{
    private $_model;

    public function __construct(Item $item)
    {
        parent::__construct();
        $this->_model = $item;
        $this->middleware('permission:Meals', ['only' => ['index', 'create', 'edit']]);
        $this->validationRules["option"] = 'required|array';
        foreach (config('translatable.locales') as $local) {
            $this->validationRules["name.$local"] = 'required';
            $this->validationRules["description.$local"] = 'nullable';
            $this->validationRules["option.*.name.$local"] = 'required';
            $this->validationMessages["option.*.name.$local.required"] = t("$local Name Required");
        }
        $this->validationRules["option_category"] = 'nullable|exists:option_categories,id';
        $this->validationRules["classification_id"] = 'required|exists:classifications,id';
        $this->validationRules["image"] = 'nullable|image';
        $this->validationRules["calories"] = 'required';
        $this->validationRules["option.*.price"] = 'required';
        $this->validationRules["option.*.ordered"] = 'required';
        $this->validationMessages["option.*.price.required"] = t("Price Required");
        $this->validationMessages["option.*.ordered.required"] = t("Ordered Required");
    }

    public function index()
    {
        $title = t('Show Meals');
        if (request()->ajax()) {
            $name = request()->get('name', false);
            $branch = request()->get('branch', false);
            $classification = request()->get('classification', false);
            $status = request()->get('status', false);


            $items = $this->_model
                ->where('merchant_id', user('merchant')->id)
                ->when($name, function ($query) use ($name) {
                    $query->where('name->' . lang(), 'like', '%' . $name . '%');
                })->when($branch, function ($query) use ($branch) {
                    $query->where('branch_id', $branch);
                })->when($classification, function ($query) use ($classification) {
                    $query->where('classification_id', $classification);
                })
                ->when($status, function ($query) use ($status) {
                    $query->where('draft', $status);
                })->latest();

            return DataTables::make($items)
                ->escapeColumns([])
                ->addColumn('name', function ($item) {
                    return $item->name;
                })
                ->addColumn('merchant', function ($item) {
                    return optional($item->merchant)->name;
                })
                ->addColumn('branches', function ($item) {
                    return optional($item->branch)->name;
                })
                ->addColumn('classification', function ($item) {
                    return optional($item->classification)->name;
                })
                ->addColumn('price', function ($item) {
                    return optional($item->prices()->first())->price;
                })
                ->addColumn('actions', function ($item) {
                    return $item->action_buttons;
                })
                ->addColumn('created_at', function ($item) {
                    return Carbon::parse($item->created_at)->toDateTimeString();
                })
                ->make();
        }
        $branches = Branch::where('merchant_id', user('merchant')->id)->get();
        return view('restaurant.item.index', compact('title', 'branches'));
    }

    public function create()
    {
        $title = t('Add Meal');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $branches = Branch::where('merchant_id', user('merchant')->id)->get();
        $options_categories = OptionCategory::query()->get();
        return view('restaurant.item.edit', compact('title', 'validator', 'branches', 'options_categories'));
    }


    public function edit($id)
    {
        $title = t('Edit Meal');
        $meal = $this->_model->with(['merchant', 'branches'])->findOrFail($id);
        $this->validationRules["image"] = 'nullable|image';
        $this->validationRules["option"] = 'nullable|array';
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $branches = Branch::notDraft()->where('merchant_id', $meal->merchant_id)->get();
        $classifications = Classification::notDraft()->where('branch_id', $meal->branch_id)->get();
        $options_categories = OptionCategory::query()->get();
        $option_category = ItemPrice::query()->where('item_id', $id)->first();

        return view('restaurant.item.edit', compact('title', 'validator',
            'meal', 'restaurants', 'classifications', 'options_categories', 'option_category', 'branches'/*, 'branchesIds'*/));

    }


    public function store(Request $request)
    {
        if (isset($request->item_id)) {
            $this->validationRules["image"] = 'nullable|image';
            $this->validationRules["option"] = 'nullable|array';
            $store = $this->_model->findOrFail($request->item_id);
        } else {
            $store = new $this->_model();
        }
        $request->validate($this->validationRules);
        $store->name = $request->name;
        $store->description = $request->description;
        $store->classification_id = $request->classification_id;
        $store->calories = $request->calories;
        $store->merchant_id = user('merchant')->id;
        $store->branch_id = $request->branch_id;
        $store->has_discount = $request->get('has_discount', 0);
        $store->discount = isset($store->has_discount) && $store->has_discount == true ? $request->get('discount') : 0;

        $store->draft = $request->get('draft', 0);
        if ($request->hasFile('image')) {
            $store->image = $this->uploadImage($request->file('image'), 'meals');
        }
        $store->save();
        $option_category = $request->get('option_category', null);
        /*
         *
         *old add ons
         */
        $item_prices = $request->get('old_option', []);
        $item_addons = $request->get('old_addon', []);
        $newArr_prices = array_keys($item_prices);
        $newArr_addons = array_keys($item_addons);
        $db_prices = $store->prices->pluck('id')->all();
        $db_addons = $store->addons->pluck('id')->all();
        foreach ($db_prices as $index => $db_price) {
            if (!in_array($db_price, $newArr_prices)) ItemPrice::where('id', $db_price)->delete();
        }
        foreach ($db_addons as $index => $db_addon) {
            if (!in_array($db_addon, $newArr_addons)) ItemAddon::where('id', $db_addon)->delete();
        }
        foreach ($item_prices as $key => $item_price) {
            $item_price["item_id"] = $store->id;
            $item_price["draft"] = isset($item_price['draft']) ? 1 : 0;
            $item_price["option_category_id"] = $option_category;
            $price = ItemPrice::query()->find($key);
            if ($price) $price->update($item_price);
        }
        foreach ($item_addons as $key => $item_addon) {
            $item_addon["item_id"] = $store->id;
            $item_addon["draft"] = isset($item_addon['draft']) ? 1 : 0;
            $addon = ItemAddon::query()->find($key);
            if ($addon) $addon->update($item_addon);
        }
        /***
         *
         */

        $item_prices = $request->get('option', []);
        foreach ($item_prices as $key => $item_price) {
            $item_price["item_id"] = $store->id;
            $item_price["option_category_id"] = $option_category;
            ItemPrice::create($item_price);
        }
        $item_addons = $request->get('addon', []);
        foreach ($item_addons as $key => $item_addon) {
            $item_addon["item_id"] = $store->id;
            ItemAddon::create($item_addon);
        }

        $min_price = ItemPrice::query()->whereHas('item', function ($query) use ($store, $request) {
            $query->where('merchant_id', user('merchant')->id);
        })->min('price');

        $branch = $store->branch;
        if ($branch) $branch->update(['min_price' => $min_price,]);
        if (isset($request->item_id)) {
            return redirect()->route('restaurant.meal.index')->with('m-class', 'success')->with('message', t('Successfully Updated'));
        } else {
            return redirect()->route('restaurant.meal.index')->with('m-class', 'success')->with('message', t('Successfully Created'));
        }
    }


    public function update(Request $request, $id)
    {
        $this->validationRules["image"] = 'nullable|image';
        $this->validationRules["option"] = 'nullable|array';
        $request->validate($this->validationRules, $this->validationMessages);
        $meal = Item::query()->findOrFail($id);
        $data = $request->all();
        $data['draft'] = $request->get('draft', 0);
        $data['user_id'] = $data['branch_id'];
        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadImage($request->file('image'), 'meals');
        }
        $meal->update($data);
        $option_category = $request->get('option_category', null);

        $item_prices = $request->get('old_option', []);
        foreach ($item_prices as $key => $item_price) {
            $item_price["item_id"] = $meal->id;
            $item_price["draft"] = isset($item_price['draft']) ? 1 : 0;
            $item_price["option_category_id"] = $option_category;
            $price = ItemPrice::query()->find($key);
            if ($price) {
                $price->update($item_price);
            } else {
                ItemPrice::query()->create($item_price);
            }
        }

        $item_addons = $request->get('old_addon', []);
        foreach ($item_addons as $key => $item_addon) {
            $item_addon["item_id"] = $meal->id;
            $item_addon["draft"] = isset($item_addon['draft']) ? 1 : 0;
            $addon = ItemAddon::query()->find($key);
            if ($addon) {
                $addon->update($item_addon);
            } else {
                ItemAddon::create($item_addon);
            }
        }

        $item_prices = $request->get('option', []);
        foreach ($item_prices as $key => $item_price) {
            $item_price["item_id"] = $meal->id;
            $item_price["option_category_id"] = $option_category;
            ItemPrice::create($item_price);
        }
        $item_addons = $request->get('addon', []);
        foreach ($item_addons as $key => $item_addon) {
            $item_addon["item_id"] = $meal->id;
            ItemAddon::create($item_addon);
        }

        $min_price = ItemPrice::query()->whereHas('item', function ($query) use ($data) {
            $query->where('user_id', $data['user_id']);
        })->min('price');

        $branch = Branch::query()->where('user_id', $data['user_id'])->first();
        if ($branch) {
            $branch->update([
                'min_price' => $min_price,
            ]);
        }

        return redirect()->route('restaurant.meal.index')->with('message', t('Successfully Updated'))->with('m-class', 'success');
    }

    public function destroy($id)
    {
        $item = Item::query()->findOrFail($id);
        $item_orders = OrderItem::query()->where('item_id', $id)->count();
        if ($item_orders > 0)
            return redirect()->back()->with('message', t('Can\'t Delete Meal, Meal Related With Orders'))->with('m-class', 'error');

        $item->delete();
        $item->delete();

        return redirect()->back()->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }

    public function deleteAddon($id)
    {
        $addon = ItemAddon::query()->find($id);
        if (!$addon)
            return $this->sendError(t('Addon Not Found'));

        $order_addons = OrderItemAddon::query()->where('item_addon_id', $id)->count();
        if ($order_addons > 0)
            return $this->sendError(t('Cant\' Delete Addon, it\'s Related With Order'));

        $addon->delete();
        return $this->sendResponse(null, t('Successfully Deleted'));

    }

    public function deletePrice($id)
    {
        $price = ItemPrice::query()->find($id);
        if (!$price)
            return $this->sendError(t('Price Option Not Found'));

        $order_prices = OrderItem::query()->where('item_price_id', $id)->count();
        if ($order_prices > 0)
            return $this->sendError(t('Cant\' Delete Price Option, it\'s Related With Order'));

        //$price->delete();
        return $this->sendResponse(null, t('Successfully Deleted'));
    }

}
