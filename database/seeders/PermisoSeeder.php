<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Rol;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermisoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Rol::create([            
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
            'password' => bcrypt('password')
        ]);

        User::create([
            'name' => 'Renzo Siza Tejada', 
            'email' => 'rsiza@unsa.edu.pe',
            'password' => bcrypt('password')
        ]);

        //usuario administrador
        $user1 = User::find(1);
        $user1->assignRole('Administrador');
        $user2 = User::find(2);
        $user2->assignRole('Administrador');
        $user3 = User::find(3);
        $user3->assignRole('Administrador');

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'Listar Roles', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'Crear Roles', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'Mostrar Roles', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'Editar Roles', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'Eliminar Roles', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'Restaurar Roles', 'guard_name' => 'sanctum']);

        Permission::create(['name' => 'Listar Usuarios', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'Crear Usuarios', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'Mostrar Usuarios', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'Editar Usuarios', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'Eliminar Usuarios', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'Restaurar Usuarios', 'guard_name' => 'sanctum']);

        Permission::create(['name' => 'Listar Unidades-Medida', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'Crear Unidades-Medida', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'Mostrar Unidades-Medida', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'Editar Unidades-Medida', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'Eliminar Unidades-Medida', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'Restaurar Unidades-Medida', 'guard_name' => 'sanctum']);

        Permission::create(['name' => 'Listar Clasificadores', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'Crear Clasificadores', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'Mostrar Clasificadores', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'Editar Clasificadores', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'Eliminar Clasificadores', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'Restaurar Clasificadores', 'guard_name' => 'sanctum']);

        Permission::create(['name' => 'Listar Tipos-Concepto', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'Crear Tipos-Concepto', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'Mostrar Tipos-Concepto', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'Editar Tipos-Concepto', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'Eliminar Tipos-Concepto', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'Restaurar Tipos-Concepto', 'guard_name' => 'sanctum']);

        Permission::create(['name' => 'Listar Conceptos', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'Crear Conceptos', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'Mostrar Conceptos', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'Editar Conceptos', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'Eliminar Conceptos', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'Restaurar Conceptos', 'guard_name' => 'sanctum']);

        Permission::create(['name' => 'Listar Tipos-Comprobante', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'Crear Tipos-Comprobante', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'Mostrar Tipos-Comprobante', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'Editar Tipos-Comprobante', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'Eliminar Tipos-Comprobante', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'Restaurar Tipos-Comprobante', 'guard_name' => 'sanctum']);
    }
}

