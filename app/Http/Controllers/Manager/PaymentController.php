<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use App\Models\MerchantType;
use App\Models\Provider;
use App\Models\Payment;
use App\Notifications\GeneralWithTitleAsArrayNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class PaymentController extends Controller
{
    private $_model;

    public function __construct(Payment $payment)
    {
        parent::__construct();
        $this->_model = $payment;
        $this->middleware('permission:Payments', ['only' => ['create', 'store', 'show', 'edit', 'update', 'destroy']]);
        $this->validationRules["merchant_id"] = 'required|exists:merchants,id';
        $this->validationRules["amount"] = 'required|min:0';
        $this->validationRules["note"] = 'nullable';
    }

    public function index(Request $request)
    {
        $title = t('Show Payment');
        $merchant = $request->get('merchant', false);
        $date_start = $request->get('date_start', false);
        $date_end = $request->get('date_end', false);

        $payments = $this->_model->when($merchant, function ($query) use ($merchant) {
            $query->where('merchant_id', $merchant);
        })->when($date_start, function ($query) use ($date_start) {
            $query->whereDate('created_at', '>=', Carbon::parse($date_start));
        })->when($date_end, function ($query) use ($date_end) {
            $query->whereDate('created_at', '<=', Carbon::parse($date_end));
        });

        if (request()->ajax()) {
            return DataTables::make($payments)
                ->escapeColumns([])
                ->addColumn('created_at', function ($payment) {
                    return Carbon::parse($payment->created_at)->toDateTimeString();
                })
                ->addColumn('merchant', function ($payment) {
                    return optional($payment->merchant)->name;
                })
                ->addColumn('actions', function ($payment) {
                    return $payment->action_buttons;
                })
                ->make();
        }
        $total = $payments->sum('amount');
        $merchants = Merchant::query()->get();
        return view('manager.payment.index', compact('title', 'merchants', 'total'));
    }

    public function create()
    {
        $title = t('Add Payment');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $merchants = Merchant::all();
        return view('manager.payment.edit', compact('title', 'merchants', 'validator'));
    }

    public function store(Request $request)
    {
        if (isset($request->payment_id)) $store = $this->_model->findOrFail($request->payment_id);
        else $store = new $this->_model();
        $request->validate($this->validationRules);
        $store->merchant_id = (int)$request->merchant_id;
        $provider = Merchant::findOrFail($request->merchant_id);
        $store->amount = $request->amount;
        $store->note = $request->note;
        $store->save();
        $title = [
            'ar' => t('New Payment'),
            'en' => t('New Payment'),
        ];
        $body = [
            'ar' => t('New Payment Added to your account by', ['payment' => $request->amount], 'ar'),
            'en' => t('New Payment Added to your account by', ['payment' => $request->amount], 'en'),
        ];
        if (isset($request->item_id)) {
            return redirect()->route('manager.payment.index')->with('m-class', 'success')->with('message', t('Successfully Updated'));
        } else {
            \Illuminate\Support\Facades\Notification::send($provider, new GeneralWithTitleAsArrayNotification(null, $title, $body));
            return redirect()->route('manager.payment.index')->with('m-class', 'success')->with('message', t('Successfully Created'));
        }
    }

    public function edit($id)
    {
        $title = t('Edit Payment');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $merchants = Provider::all();
        $payment = Payment::query()->findOrFail($id);
        return view('manager.payment.edit', compact('title', 'validator', 'merchants', 'payment'));
    }

    public function destroy($id)
    {
        $payment = Payment::query()->findOrFail($id);
        $payment->delete();
        return redirect()->route('manager.payment.index')->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }
}
