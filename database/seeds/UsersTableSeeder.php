<?php

use Illuminate\Database\Seeder;
use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Cria um usuário
        $user = User::create([
            'name'     => '011 Brasil',
            'email'    => 'marcelo.cabral@011brasil.com.br',
            'password' => bcrypt('fullfreela'),
            "ativo"    => '1',
            'marcador' => '@marcelo.cabral'
        ]);
        $user_dev = User::create([
            'name'     => 'Desenvolvedor',
            'email'    => 'dev@011brasil.com.br',
            'password' => bcrypt('fullfreela'),
            "ativo"    => '1',
            'marcador' => '@dev'
        ]);
        $user_admin = User::create([
            'name'     => 'Leonardo Bartz',
            'email'    => 'dlleobartz@gmail.com',
            'password' => bcrypt('Mint123!'),
            "ativo"    => '1',
            'marcador' => '@dlleobartz'
        ]);

        // Tests
        $user_free = User::create([
            'name'     => 'Freela',
            'email'    => 'freela@011brasil.com.br',
            'password' => bcrypt('fullfreela'),
            "ativo"    => '1',
            'marcador' => '@freela'
        ]);
        $user_pub = User::create([
            'name'     => 'Publicador',
            'email'    => 'publicador@011brasil.com.br',
            'password' => bcrypt('fullfreela'),
            "ativo"    => '1',
            'marcador' => '@pub'
        ]);

        // Delega política para o usuário
        $role        = Role::where('name', 'desenvolvedor')->with('permissions')->get()->first();
        $permissions = $role->permissions;
        
        $user->roles()->sync($role);
        $user->permissions()->sync($permissions);
        
        $user_dev->roles()->sync($role);
        $user_dev->permissions()->sync($permissions);

        $role        = Role::where('name', 'admin')->with('permissions')->get()->first();
        $permissions = $role->permissions;

        $user_admin->roles()->sync($role);
        $user_admin->permissions()->sync($permissions);

        $role        = Role::where('name', 'publicador')->with('permissions')->get()->first();
        $permissions = $role->permissions;

        $user_pub->roles()->sync($role);
        $user_pub->permissions()->sync($permissions);

        $role        = Role::where('name', 'freelancer')->with('permissions')->get()->first();
        $permissions = $role->permissions;

        $user_free->roles()->sync($role);
        $user_free->permissions()->sync($permissions);


    }
}
