<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class AddressController extends Controller
{
    private $_model;

    public function __construct(Address $address)
    {
        parent::__construct();
        $this->_model = $address;
        $this->validationRules["user_id"] = 'required';
        $this->validationRules["name"] = 'required';
        $this->validationRules["address"] = 'required';
        $this->validationRules["type"] = 'required';
        $this->validationRules["lat"] = 'required';

    }

    public function index()
    {
        $title = t('Show Addresses');
        if (request()->ajax()) {
            $search = request()->get('search', false);
            $user = request()->get('user_id', false);

            $addresses = Address::query()->when($user, function ($query) use ($user) {
                $query->where('user_id', $user);
            })->withoutGlobalScope('ordered')
                ->latest()
                ->when($search, function ($query) use ($search) {
                $query->whereHas('user', function ($query) use ($search) {
                    $query->where('name->' . lang(), 'like', '%' . $search . '%');
                });
            });
            return DataTables::make($addresses)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateTimeString();
                })
                ->addColumn('user', function ($row) {
                    return optional($row->user)->name;
                })
                ->addColumn('type_name', function ($row) {
                    return $row->type_name;
                })
                ->addColumn('actions', function ($row) {
                    return $row->action_buttons;
                })
                ->make();
        }
        return view('manager.address.index', compact('title'));
    }

    public function create()
    {
        $this->validationMessages["lat.required"] = t('Address Required On Map');
        $this->validationMessages["lng.required"] = t('Address Required On Map');
        $title = t('Add Address');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $users = User::query()->where('type', 'user')->get();
        return view('manager.address.edit', compact('title', 'validator', 'users'));
    }

    public function store(Request $request)
    {
        $this->validationMessages["lat.required"] = t('Address Required On Map');
        $this->validationMessages["lng.required"] = t('Address Required On Map');
        $request->validate($this->validationRules, $this->validationMessages);
        $data = $request->all();
        $data['default'] = $request->get('default', 0);
        Address::create($data);
        return redirect()->route('manager.address.index')->with('m-class', 'success')->with('message', t('Successfully Created'));
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $title = t('Edit Address');
        $this->validationMessages["lat.required"] = t('Address Required On Map');
        $this->validationMessages["lng.required"] = t('Address Required On Map');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $address = Address::query()->findOrFail($id);
        $users = User::query()->where('type', 'user')->get();
        return view('manager.address.edit', compact('title', 'address', 'validator', 'users'));
    }

    public function update(Request $request, $id)
    {
        $this->validationMessages["lat.required"] = t('Address Required On Map');
        $this->validationMessages["lng.required"] = t('Address Required On Map');
        $request->validate($this->validationRules, $this->validationMessages);
        $address = Address::query()->findOrFail($id);
        $data = $request->all();
        $data['default'] = $request->get('default', 0);
        $address->update($data);
        return redirect()->route('manager.address.index')->with('m-class', 'success')->with('message', t('Successfully Updated'));

    }

    public function destroy($id)
    {
        $address = Address::query()->findOrFail($id);
        $address->delete();
        return redirect()->back()->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }
}
