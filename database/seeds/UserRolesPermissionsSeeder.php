<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserRolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tableNames = config('permission.table_names');

        // first delete the old roles (when updating)
        DB::table($tableNames['role_has_permissions'])->delete();
        DB::table($tableNames['model_has_roles'])->delete();
        DB::table($tableNames['model_has_permissions'])->delete();
        DB::table($tableNames['roles'])->delete();
        DB::table($tableNames['permissions'])->delete();

        // now the table is free to create the permissions..
        // Permission::create(['name' => 'manage songs']);
        // Permission::create(['name' => 'manage authors']);
        // Permission::create(['name' => 'manage externals']);
        Permission::create(['name' => 'manage users']);

        // .. and roles
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo('manage users');
    }
}
