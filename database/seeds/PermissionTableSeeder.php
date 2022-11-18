<?php

use Illuminate\Database\Seeder;
use \Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    public function run()
    {
        $this->managerPermissions();
        $this->merchantPermissions();
        $this->branchPermissions();
    }

    private function managerPermissions()
    {
        $permissions = MANAGER_PERMISSIONS;
        foreach ($permissions as $permission) {
            if (Permission::where('name', $permission)->where('guard_name', 'manager')->count() == 0) Permission::create(['name' => $permission, 'guard_name' => 'manager']);
            foreach (\App\Models\Manager::get() as $index => $item) {
                if (!$item->can($permission)) $item->givePermissionTo($permission);
            }
        }
    }

    private function merchantPermissions()
    {
        $provider_permissions = MERCHANT_PERMISSIONS;
        foreach ($provider_permissions as $permission) {
            if (Permission::where('name', $permission)->where('guard_name', 'merchant')->count() == 0) Permission::create(['name' => $permission, 'guard_name' => 'merchant']);
            foreach (\App\Models\Merchant::get() as $index => $item) {
                if (!$item->can($permission)) $item->givePermissionTo($permission);
            }
        }
    }

    private function branchPermissions()
    {
        $branch_permissions = BRANCH_PERMISSIONS;
        foreach ($branch_permissions as $permission) {
            if (Permission::where('name', $permission)->where('guard_name', 'branch')->count() == 0)
                Permission::create(['name' => $permission, 'guard_name' => 'branch']);
            foreach (\App\Models\Branch::get() as $index => $item) {
                if (!$item->can($permission)) $item->givePermissionTo($permission);
            }
        }
    }
}
