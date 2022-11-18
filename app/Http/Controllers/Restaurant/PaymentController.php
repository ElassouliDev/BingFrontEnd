<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PaymentController extends Controller
{
    private $_model;

    public function __construct(Payment $payment)
    {
        parent::__construct();
        $this->_model = $payment;
        $this->middleware('permission:Payments', ['only' => ['show']]);
        $this->validationRules["merchant_id"] = 'required|exists:providers,id';
        $this->validationRules["amount"] = 'required|min:0';
        $this->validationRules["note"] = 'nullable';
    }

    public function index(Request $request)
    {
        $title = t('Show Payment');
        $merchant = user('merchant')->id;
        $date_start = $request->get('date_start', false);
        $date_end = $request->get('date_end', false);

        $payments = $this->_model->where('merchant_id', $merchant)
            ->when($date_start, function ($query) use ($date_start) {
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
        return view('restaurant.payment.index', compact('title', 'total'));
    }


}
