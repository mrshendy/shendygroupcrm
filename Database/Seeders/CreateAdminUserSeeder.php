<?php
 namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\User;
use Spatie\Permission\models\Role;
use Spatie\Permission\models\Permission;
class CreateAdminUserSeeder extends Seeder
{
/**
* Run the database seeds.
*
* @return void
*/
public function run()
{
    
         $user = User::create([
        'name' => 'mohamed shendy', 
        'email' => 'mohamedshendy448@gmail.com',
        'password' => bcrypt('123456'),
        'roles_name' => ["owner"],
        'Status' => 'Enabled',
        'id_account' => '1',
        'employee_id' => '1',
        ]);
        $role = Role::create(['name' => 'owner']);
   
        $permissions = Permission::pluck('id','id')->all();
  
        $role->syncPermissions($permissions);
   
        $user->assignRole([$role->id]);


}
}