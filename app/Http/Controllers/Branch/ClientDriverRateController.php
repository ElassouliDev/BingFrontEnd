<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\ClientDriverRate;
use App\Models\Merchant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ClientDriverRateController extends Controller
{
    private $_model;

    public function __construct(ClientDriverRate $clientDriverRate)
    {
        parent::__construct();
        $this->_model = $clientDriverRate;
        $this->middleware('permission:Ratings', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $title = t('Driver Rates');
        if (request()->ajax()) {
            $driver = $request->get('driver', false);
            $rates = $this->_model->when($driver, function ($query1) use ($driver) {
                $query1->whereHas('driver', function ($query) use ($driver) {
                    $query->where('id', $driver)->where('branch_id', user('branch')->id);
                });
            })->latest('updated_at');

            return DataTables::make($rates)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateTimeString();
                })
                ->addColumn('user', function ($row) {
                    return optional(optional($row->order)->user)->name;
                })
                ->addColumn('driver_name', function ($row) {
                    return optional(optional($row->delivery)->driver)->name;
                })
                ->addColumn('actions', function ($row) {
                    return '';//$row->action_buttons;
                })
                ->addColumn('uuid', function ($row) {
                    return optional($row->order)->uuid;
                })
                ->addColumn('comment', function ($row) {
                    return $row->content_rating;
                })
                ->addColumn('rate', function ($row) {
                    return $row->stars_number . '/5';
                })
                ->make();
        }
        return view('branch.rate.client_driver_rate', compact('title'));
    }

    public function destroy($id)
    {
        $rate = $this->_model->findOrFail($id);
        $rate->delete();
        return redirect()->route('branch.client_driver_rate.index')->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }
}
