<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:Roles', ['only' => ['index','create','edit', 'destroy']]);
    }

    public function index(Request $request)
    {
        $title = t('Show Role List');
        $roles = Role::query()->where('guard_name', 'manager')->orderBy('id','DESC')->paginate(10);
        return view('manager.roles.index',compact('roles', 'title'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        $title = t('Create New Role');
        $permissions = Permission::query()->where('guard_name', 'manager')->get()->chunk(4);
        return view('manager.roles.create',compact('permissions','title'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);
        $role = Role::create(['guard_name' => 'manager','name' => $request->input('name')]);
        $role->syncPermissions($request->input('permission'));
        return redirect()->route('manager.manager_roles.index')
            ->with('message',t('Successfully Added'))->with('m-class', 'success');
    }

    public function show($id)
    {
        $title = t('Show Role');
        $role = Role::query()->find($id);
        $rolePermissions = $role->permissions()->get()->chunk(4);
        return view('manager.roles.show',compact('role','rolePermissions', 'title'));
    }

    public function edit($id)
    {
        $title = t('Edit Role');
        $role = Role::find($id);
        $permissions = Permission::query()->where('guard_name', 'manager')->get()->chunk(4);
        $rolePermissions = $role->permissions()->get()
            ->pluck('id')
            ->all();
        return view('manager.roles.edit',compact('role','permissions','rolePermissions','title'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);


        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();


        $role->syncPermissions($request->input('permission'));


        return redirect()->route('manager.manager_roles.index')
            ->with('message',t('Successfully Updated'))->with('m-class', 'success');
    }

    public function destroy($id)
    {
        DB::table("roles")->where('id',$id)->delete();
        return redirect()->route('manager.manager_roles.index')
            ->with('message', t('Successfully Deleted'))->with('m-class', 'success');
    }
}
