<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;

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

        // DB::table($tableNames['role_has_permissions'])->delete();
        // DB::table($tableNames['model_has_roles'])->delete();
        // DB::table($tableNames['model_has_permissions'])->delete();
        // DB::table($tableNames['roles'])->delete();
        // DB::table($tableNames['permissions'])->delete();

        $permissions = ['manage users', 'manage official tags', 'access todo', 'add authors', 'manage tags', 'publish songs', 'approve songs'];

        // if the permission already exists then do not create new one
        foreach ($permissions as $new_name)
        {
            $new_perm = Permission::firstOrNew(['name' => $new_name]);
            $new_perm->save();
        }

        // .. and roles
        $admin = Role::firstOrNew(['name' => 'admin']);
        foreach ($permissions as $perm) {
            $admin->givePermissionTo($perm);
        }
        $admin->save();

        $editor = Role::firstOrNew(['name' => 'editor']);
        $editor->givePermissionTo('access todo');
        $editor->givePermissionTo('add authors');
        $editor->givePermissionTo('manage tags');
        $editor->givePermissionTo('publish songs');
        $editor->save();

        $author = Role::firstOrNew(['name' => 'autor']);
        $author->givePermissionTo('approve songs');
        $author->save();

        // set some roles
        $admins_to_be = [
            User::where('email', 'michaeldojcar@gmail.com')->first(),
            User::where('email', 'athes01@gmail.com')->first()
        ];

        foreach ($admins_to_be as $admin_to_be) {
            if (isset($admin_to_be)) {
                $admin_to_be->assignRole($admin);
                $admin_to_be->save();
            }
        }
    }
}
