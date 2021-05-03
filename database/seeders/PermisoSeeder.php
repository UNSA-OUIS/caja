<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Rol;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
//use Spatie\Permission\Models\Role;

class PermisoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superadmin=Rol::create([            
            'name' => 'Administrador',
            'guard_name' => 'sanctum'                                                          
        ]);
        
        User::create([
            'name' => 'Jesus Ortiz Chavez', 
            'email' => 'jortiz@unsa.edu.pe',
            'password' => bcrypt('password')
        ]);

        User::create([
            'name' => 'Gary Núñez Ccahuaya', 
            'email' => 'gnunezc@unsa.edu.pe',
            'email_personal' => 'garyfnc185@gmail.com',
            'direccion' => 'Urb. las orquideas',
            'celular' => '987314950',
            'password' => bcrypt('password')
        ]);

        User::create([
            'name' => 'Renzo Siza Tejada', 
            'email' => 'rsiza@unsa.edu.pe',
            'password' => bcrypt('password')
        ]);

        User::create([
            'name' => 'Edson Lipa Urbina', 
            'email' => 'elipau@unsa.edu.pe',
            'password' => bcrypt('password')
        ]);

        //usuario administrador
        $user1 = User::find(1);
        $user1->assignRole('Administrador');
        $user2 = User::find(2);
        $user2->assignRole('Administrador');
        $user3 = User::find(3);
        $user3->assignRole('Administrador');
        $user4 = User::find(4);
        $user4->assignRole('Administrador');

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'Listar Roles', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Crear Roles', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Mostrar Roles', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Editar Roles', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Eliminar Roles', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Restaurar Roles', 'guard_name' => 'sanctum'])->assignRole($superadmin);

        Permission::create(['name' => 'Listar Usuarios', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Crear Usuarios', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Mostrar Usuarios', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Editar Usuarios', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Eliminar Usuarios', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Restaurar Usuarios', 'guard_name' => 'sanctum'])->assignRole($superadmin);

        Permission::create(['name' => 'Listar Unidades-Medida', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Crear Unidades-Medida', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Mostrar Unidades-Medida', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Editar Unidades-Medida', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Eliminar Unidades-Medida', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Restaurar Unidades-Medida', 'guard_name' => 'sanctum'])->assignRole($superadmin);

        Permission::create(['name' => 'Listar Clasificadores', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Crear Clasificadores', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Mostrar Clasificadores', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Editar Clasificadores', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Eliminar Clasificadores', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Restaurar Clasificadores', 'guard_name' => 'sanctum'])->assignRole($superadmin);

        Permission::create(['name' => 'Listar Tipos-Concepto', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Crear Tipos-Concepto', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Mostrar Tipos-Concepto', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Editar Tipos-Concepto', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Eliminar Tipos-Concepto', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Restaurar Tipos-Concepto', 'guard_name' => 'sanctum'])->assignRole($superadmin);

        Permission::create(['name' => 'Listar Conceptos', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Crear Conceptos', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Mostrar Conceptos', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Editar Conceptos', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Eliminar Conceptos', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Restaurar Conceptos', 'guard_name' => 'sanctum'])->assignRole($superadmin);

        Permission::create(['name' => 'Listar Tipos-Comprobante', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Crear Tipos-Comprobante', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Mostrar Tipos-Comprobante', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Editar Tipos-Comprobante', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Eliminar Tipos-Comprobante', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Restaurar Tipos-Comprobante', 'guard_name' => 'sanctum'])->assignRole($superadmin);

        Permission::create(['name' => 'Listar Particulares', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Crear Particulares', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Mostrar Particulares', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Editar Particulares', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Eliminar Particulares', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Restaurar Particulares', 'guard_name' => 'sanctum'])->assignRole($superadmin);

        Permission::create(['name' => 'Listar Empresas', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Crear Empresas', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Mostrar Empresas', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Editar Empresas', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Eliminar Empresas', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Restaurar Empresas', 'guard_name' => 'sanctum'])->assignRole($superadmin);

        Permission::create(['name' => 'Listar Dependencias', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Crear Dependencias', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Mostrar Dependencias', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Editar Dependencias', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Eliminar Dependencias', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Restaurar Dependencias', 'guard_name' => 'sanctum'])->assignRole($superadmin);

    }
}

