<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;

use App\Models\UserPackage;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;

class UserPackagesController extends Controller
{
    private $_model;

    public function __construct(UserPackage $userPackage)
    {
        parent::__construct();
        $this->_model = $userPackage;
        $this->middleware('permission:User Packages', ['only' => ['index', 'create', 'edit']]);
    }

    public function index()
    {
        $title = t('Show User User Packages');
        if (request()->ajax()) {
            $status = request()->get('status', false);
            $user = request()->get('user', false);
            $package = request()->get('package', false);
            $date_start = request()->get('date_start', false);
            $date_end = request()->get('date_end', false);
//dd(checkRequestIsWorkingOrNot());
            $items = $this->_model
                ->when($user, function ($query) use ($user) {
                    $query->wherehas('user', function ($query) use ($user) {
                        $query->where('name->' . lang(), 'like', '%' . $user . '%');
                    });
                })
                ->when($package, function ($query) use ($package) {
                    $query->wherehas('package', function ($query) use ($package) {
                        $query->where('name->' . lang(), 'like', '%' . $package . '%');
                    });
                })

                ->when($date_start, function ($query) use ($date_start) {
                    $query->whereDate('expire_date', '>=', Carbon::parse($date_start));
                })
                ->when($date_end, function ($query) use ($date_end) {
                    $query->whereDate('expire_date', '<=', Carbon::parse($date_end));
                })


                ->when($status != null, function ($query) use ($status) {
                    $query->where('expired', $status);
                })
//                ->withoutGlobalScope('notDraft')
                ->latest();

            return DataTables::make($items)
                ->escapeColumns([])
                ->addColumn('name', function ($item) {
                    return optional($item->user)->name;
                })
                ->addColumn('package', function ($item) {
                    return optional($item->package)->name;
                })
                ->addColumn('expire_date', function ($item) {
                    return Carbon::parse($item->expire_date)->format(DATE_FORMAT);
                })
                ->addColumn('status', function ($item) {
                    return $item->status_name;
                })
                ->addColumn('actions', function ($item) {
                    return $item->action_buttons;
                })
                ->addColumn('created_at', function ($item) {
                    return Carbon::parse($item->created_at)->toDateTimeString();
                })
                ->make();
        }
        return view('manager.user_packages.index', compact('title'));
    }

    public function create()
    {
        $title = t('Add User Package');
        return view('manager.user_packages.edit', compact('title'));
    }


    public function destroy($id)
    {
        $item = $this->_model->with(['users'])->findOrFail($id);
        if ($item->users()->count() > 0) return redirect()->back()->with('message', t('Can not Delete User Package, User Package Related With Users'))->with('m-class', 'error');
        $item->delete();
        return redirect()->back()->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }



    public function reactive($id)
    {
        $item = $this->_model->with(['package'])->findOrFail($id);
        $package = $item->package;
        $newExpireDate = Carbon::parse($item->expire_date)->addMonths($package->months)->format(DATE_FORMAT);
        $item->update([
            'expired' => false,
            'expire_date' => $newExpireDate,
        ]);
        return redirect()->back()->with('m-class', 'success')->with('message', t('Successfully Reactivated'));
    }

    public function active($id)
    {
        $item = $this->_model->findOrFail($id);
        $item->update([
            'expired' => false
        ]);
        return redirect()->back()->with('m-class', 'success')->with('message', t('Successfully Activated'));
    }

    public function expire($id)
    {
        $item = $this->_model->findOrFail($id);
        $item->update([
            'expired' => true
        ]);
        return redirect()->back()->with('m-class', 'success')->with('message', t('Successfully Expired'));
    }


}
