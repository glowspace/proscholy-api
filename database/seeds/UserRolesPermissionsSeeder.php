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

        // now the table is free to create the permissions..
        // Permission::firstOrNew(['name' => 'manage songs']);
        // Permission::firstOrNew(['name' => 'manage authors']);
        // Permission::firstOrNew(['name' => 'manage externals']);

        // if the permission already exists then do not create new one
        Permission::firstOrNew(['name' => 'manage users']);

        // .. and roles
        $admin = Role::firstOrNew(['name' => 'admin']);
        $admin->givePermissionTo('manage users');
        $admin->save();

        $editor = Role::firstOrNew(['name' => 'editor']);
        $editor->save();
        $author = Role::firstOrNew(['name' => 'autor']);
        $author->save();
    }
}
