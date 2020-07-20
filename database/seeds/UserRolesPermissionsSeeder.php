<?php

use App\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserRolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = ['manage users', 'manage official tags', 'access todo', 'add authors', 'manage tags', 'publish songs', 'approve songs'];

        // if the permission already exists then do not create new one
        foreach ($permissions as $new_name)
        {
            $new_perm = Permission::firstOrNew(['name' => $new_name]);
            $new_perm->save();
        }

        // .. and roles
        $admin = Role::firstOrNew(['name' => 'admin']);
        foreach ($permissions as $perm)
        {
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
            User::where('email', 'athes01@gmail.com')->first(),
            User::where('email', 'admin@admin.com')->first(),
        ];

        foreach ($admins_to_be as $admin_to_be)
        {
            if (isset($admin_to_be))
            {
                $admin_to_be->assignRole($admin);
                $admin_to_be->save();
            }
        }
    }
}
