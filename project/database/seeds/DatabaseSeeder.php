<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $admin=Role::create(['name' => 'admin']);
        $employee=Role::create(['name' => 'employee']);
        $user=Role::create(['name' => 'user']);

        $admin_user=new \App\User();
        $admin_user->first_name='admin';
        $admin_user->last_name='admin';
        $admin_user->email='admin@example.com';
        $admin_user->password=bcrypt('test123');
        $admin_user->save();

        $admin_user->assignRole($admin);

        $employee_user=new \App\User();
        $employee_user->first_name='employee';
        $employee_user->last_name='employee';
        $employee_user->email='employee@example.com';
        $employee_user->password=bcrypt('test123');
        $employee_user->save();

        $employee_user->assignRole($employee);

        $user_user=new \App\User();
        $user_user->first_name='user';
        $user_user->last_name='user';
        $user_user->email='user@example.com';
        $user_user->password=bcrypt('test123');
        $user_user->save();

        $user_user->assignRole($user);

        //USER PERMISSIONS
        $user_view_calendar=\Spatie\Permission\Models\Permission::create(['name'=>'view user calendar','role_name'=>'user']);
        $user_book_appointment=\Spatie\Permission\Models\Permission::create(['name'=>'book appointment','role_name'=>'user']);
        $user_pay=\Spatie\Permission\Models\Permission::create(['name'=>'pay','role_name'=>'user']);
        $user_view_transactions=\Spatie\Permission\Models\Permission::create(['name'=>'view user transactions','role_name'=>'user']);
        $user_edit_profile=\Spatie\Permission\Models\Permission::create(['name'=>'edit user profile','role_name'=>'user']);

        $user->givePermissionTo($user_view_calendar);
        $user->givePermissionTo($user_book_appointment);
        $user->givePermissionTo($user_pay);
        $user->givePermissionTo($user_view_transactions);
        $user->givePermissionTo($user_edit_profile);

        //EMPLOYEE PERMISSIONS
        $employee_view_calendar=\Spatie\Permission\Models\Permission::create(['name'=>'view employee calendar','role_name'=>'employee']);
        $employee_book_event=\Spatie\Permission\Models\Permission::create(['name'=>'book employee event','role_name'=>'employee']);
        $employee_edit_rules=\Spatie\Permission\Models\Permission::create(['name'=>'edit rules','role_name'=>'employee']);
        $employee_view_transactions=\Spatie\Permission\Models\Permission::create(['name'=>'view employee transactions','role_name'=>'employee']);
        $employee_view_events=\Spatie\Permission\Models\Permission::create(['name'=>'view employee events','role_name'=>'employee']);
        $employee_edit_profile=\Spatie\Permission\Models\Permission::create(['name'=>'edit employee profile','role_name'=>'employee']);

        $employee->givePermissionTo($employee_view_calendar);
        $employee->givePermissionTo($employee_book_event);
        $employee->givePermissionTo($employee_edit_rules);
        $employee->givePermissionTo($employee_view_transactions);
        $employee->givePermissionTo($employee_view_events);
        $employee->givePermissionTo($employee_edit_profile);

        //ADMIN PERMISSIONS
        $admin_view_calendars=\Spatie\Permission\Models\Permission::create(['name'=>'view all calendars','role_name'=>'admin']);
        $admin_book_appointment=\Spatie\Permission\Models\Permission::create(['name'=>'book admin appointment','role_name'=>'admin']);
        $admin_book_event=\Spatie\Permission\Models\Permission::create(['name'=>'book admin event','role_name'=>'admin']);
        $admin_view_roles=\Spatie\Permission\Models\Permission::create(['name'=>'view roles','role_name'=>'admin']);
        $admin_edit_roles=\Spatie\Permission\Models\Permission::create(['name'=>'edit roles','role_name'=>'admin']);
        $admin_view_users=\Spatie\Permission\Models\Permission::create(['name'=>'view users','role_name'=>'admin']);
        $admin_add_users=\Spatie\Permission\Models\Permission::create(['name'=>'add users','role_name'=>'admin']);
        $admin_edit_users=\Spatie\Permission\Models\Permission::create(['name'=>'edit users','role_name'=>'admin']);
        $admin_edit_rules=\Spatie\Permission\Models\Permission::create(['name'=>'edit all rules','role_name'=>'admin']);
        $admin_delete_users=\Spatie\Permission\Models\Permission::create(['name'=>'delete users','role_name'=>'admin']);
        $admin_view_events=\Spatie\Permission\Models\Permission::create(['name'=>'view all events','role_name'=>'admin']);
        $admin_delete_events=\Spatie\Permission\Models\Permission::create(['name'=>'delete events','role_name'=>'admin']);
        $admin_view_transactions=\Spatie\Permission\Models\Permission::create(['name'=>'view all transactions','role_name'=>'admin']);
        $admin_delete_transactions=\Spatie\Permission\Models\Permission::create(['name'=>'delete transactions','role_name'=>'admin']);
        $admin_edit_profile=\Spatie\Permission\Models\Permission::create(['name'=>'edit admin profile','role_name'=>'admin']);

        $admin->givePermissionTo($admin_view_calendars);
        $admin->givePermissionTo($admin_book_appointment);
        $admin->givePermissionTo($admin_book_event);
        $admin->givePermissionTo($admin_view_roles);
        $admin->givePermissionTo($admin_edit_roles);
        $admin->givePermissionTo($admin_view_users);
        $admin->givePermissionTo($admin_add_users);
        $admin->givePermissionTo($admin_edit_users);
        $admin->givePermissionTo($admin_edit_rules);
        $admin->givePermissionTo($admin_delete_users);
        $admin->givePermissionTo($admin_view_events);
        $admin->givePermissionTo($admin_delete_events);
        $admin->givePermissionTo($admin_view_transactions);
        $admin->givePermissionTo($admin_delete_transactions);
        $admin->givePermissionTo($admin_edit_profile);


        // $this->call(UsersTableSeeder::class);
    }
}
