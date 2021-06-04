<?php

namespace Database\Seeders;

use App\Models\NumeroOperacion;
use App\Models\Persona;
use App\Models\PuntosVenta;
use App\Models\User;
use App\Models\Rol;
use App\Models\TipoComprobante;
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
        $superadmin = Rol::create([
            'name' => 'Administrador',
            'guard_name' => 'sanctum'
        ]);

        $cajero = Rol::create([
            'name' => 'Cajero',
            'guard_name' => 'sanctum'
        ]);

        User::create([
            'name' => 'Caja UNSA',
            'email' => 'cajaunsa@unsa.edu.pe',
            'password' => bcrypt('cajaunsa2020')
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

        User::create([
            'name' => 'Edson Lipa Urbina',
            'email' => 'elipau@unsa.edu.pe',
            'password' => bcrypt('password')
        ]);

        User::create([
            'name' => 'Elsa del Carpio',
            'email' => 'edelcarpioa@unsa.edu.pe',
            'password' => bcrypt('password')
        ]);

        User::create([
            'name' => 'Eulalia Velasquez',
            'email' => 'evelasquezflo@unsa.edu.pe',
            'password' => bcrypt('password')
        ]);

        //usuario administrador
        $caja_unsa = User::find(1);
        $caja_unsa->assignRole('Administrador');
        $caja_unsa->assignRole('Cajero');


        $jesus = User::find(2);
        $jesus->assignRole('Administrador');
        $jesus->assignRole('Cajero');

        $gary = User::find(3);
        $gary->assignRole('Administrador');
        $gary->assignRole('Cajero');

        $renzo = User::find(4);
        $renzo->assignRole('Administrador');
        $renzo->assignRole('Cajero');

        $edson = User::find(5);
        $edson->assignRole('Administrador');
        $edson->assignRole('Cajero');

        $elsa = User::find(6);
        $elsa->assignRole('Cajero');

        $eulalia = User::find(7);
        $eulalia->assignRole('Cajero');

        TipoComprobante::create(['id' => '1', 'nombre' => 'BOLETA']);
        TipoComprobante::create(['id' => '2', 'nombre' => 'FACTURA']);
        TipoComprobante::create(['id' => '3', 'nombre' => 'NOTA DE DÉBITO']);
        TipoComprobante::create(['id' => '4', 'nombre' => 'NOTA DE CRÉDITO']);

        PuntosVenta::create([
            'ip' => '192.168.0.1',
            'nombre' => 'CUNSA01',
            'direccion' => 'Direccion CUNSA01',
            'user_id' => 1

        ]);

        PuntosVenta::create([
            'ip' => '192.168.0.2',
            'nombre' => 'CUNSA02',
            'direccion' => 'Direccion CUNSA02',
            'user_id' => 2

        ]);
        PuntosVenta::create([
            'ip' => '192.168.0.3',
            'nombre' => 'CUNSA03',
            'direccion' => 'Direccion CUNSA03',
            'user_id' => 3

        ]);
        PuntosVenta::create([
            'ip' => '192.168.0.4',
            'nombre' => 'CUNSA04',
            'direccion' => 'Direccion CUNSA04',
            'user_id' => 4

        ]);
        PuntosVenta::create([
            'ip' => '192.168.0.5',
            'nombre' => 'CUNSA05',
            'direccion' => 'Direccion CUNSA05',
            'user_id' => 5

        ]);
        PuntosVenta::create([
            'ip' => '192.168.0.6',
            'nombre' => 'CUNSA06',
            'direccion' => 'Direccion CUNSA06',
            'user_id' => 6

        ]);
        PuntosVenta::create([
            'ip' => '192.168.0.7',
            'nombre' => 'CUNSA07',
            'direccion' => 'Direccion CUNSA07',
            'user_id' => 7

        ]);

        NumeroOperacion::create([
            'serie' => 'B001',
            'correlativo' => '00000001',
            'tipo_comprobante_id' => 1,
            'punto_venta_id' => 1
        ]);
        NumeroOperacion::create([
            'serie' => 'F001',
            'correlativo' => '00000001',
            'tipo_comprobante_id' => 2,
            'punto_venta_id' => 1
        ]);
        NumeroOperacion::create([
            'serie' => 'ND01',
            'correlativo' => '00000001',
            'tipo_comprobante_id' => 3,
            'punto_venta_id' => 1
        ]);
        NumeroOperacion::create([
            'serie' => 'NC01',
            'correlativo' => '00000001',
            'tipo_comprobante_id' => 4,
            'punto_venta_id' => 1
        ]);
        NumeroOperacion::create([
            'serie' => 'B002',
            'correlativo' => '00000001',
            'tipo_comprobante_id' => 1,
            'punto_venta_id' => 2
        ]);
        NumeroOperacion::create([
            'serie' => 'F002',
            'correlativo' => '00000001',
            'tipo_comprobante_id' => 2,
            'punto_venta_id' => 2
        ]);
        NumeroOperacion::create([
            'serie' => 'ND02',
            'correlativo' => '00000001',
            'tipo_comprobante_id' => 3,
            'punto_venta_id' => 2
        ]);
        NumeroOperacion::create([
            'serie' => 'NC02',
            'correlativo' => '00000001',
            'tipo_comprobante_id' => 4,
            'punto_venta_id' => 2
        ]);
        NumeroOperacion::create([
            'serie' => 'B003',
            'correlativo' => '00000001',
            'tipo_comprobante_id' => 1,
            'punto_venta_id' => 3
        ]);
        NumeroOperacion::create([
            'serie' => 'F003',
            'correlativo' => '00000001',
            'tipo_comprobante_id' => 2,
            'punto_venta_id' => 3
        ]);
        NumeroOperacion::create([
            'serie' => 'ND03',
            'correlativo' => '00000001',
            'tipo_comprobante_id' => 3,
            'punto_venta_id' => 3
        ]);
        NumeroOperacion::create([
            'serie' => 'NC03',
            'correlativo' => '00000001',
            'tipo_comprobante_id' => 4,
            'punto_venta_id' => 3
        ]);
        NumeroOperacion::create([
            'serie' => 'B004',
            'correlativo' => '00000001',
            'tipo_comprobante_id' => 1,
            'punto_venta_id' => 4
        ]);
        NumeroOperacion::create([
            'serie' => 'F004',
            'correlativo' => '00000001',
            'tipo_comprobante_id' => 2,
            'punto_venta_id' => 4
        ]);
        NumeroOperacion::create([
            'serie' => 'ND04',
            'correlativo' => '00000001',
            'tipo_comprobante_id' => 3,
            'punto_venta_id' => 4
        ]);
        NumeroOperacion::create([
            'serie' => 'NC04',
            'correlativo' => '00000001',
            'tipo_comprobante_id' => 4,
            'punto_venta_id' => 4
        ]);
        NumeroOperacion::create([
            'serie' => 'B005',
            'correlativo' => '00000001',
            'tipo_comprobante_id' => 1,
            'punto_venta_id' => 5
        ]);
        NumeroOperacion::create([
            'serie' => 'F005',
            'correlativo' => '00000001',
            'tipo_comprobante_id' => 2,
            'punto_venta_id' => 5
        ]);
        NumeroOperacion::create([
            'serie' => 'ND05',
            'correlativo' => '00000001',
            'tipo_comprobante_id' => 3,
            'punto_venta_id' => 5
        ]);
        NumeroOperacion::create([
            'serie' => 'NC05',
            'correlativo' => '00000001',
            'tipo_comprobante_id' => 4,
            'punto_venta_id' => 5
        ]);
        NumeroOperacion::create([
            'serie' => 'B006',
            'correlativo' => '00000001',
            'tipo_comprobante_id' => 1,
            'punto_venta_id' => 6
        ]);
        NumeroOperacion::create([
            'serie' => 'F006',
            'correlativo' => '00000001',
            'tipo_comprobante_id' => 2,
            'punto_venta_id' => 6
        ]);
        NumeroOperacion::create([
            'serie' => 'ND06',
            'correlativo' => '00000001',
            'tipo_comprobante_id' => 3,
            'punto_venta_id' => 6
        ]);
        NumeroOperacion::create([
            'serie' => 'NC06',
            'correlativo' => '00000001',
            'tipo_comprobante_id' => 4,
            'punto_venta_id' => 6
        ]);
        NumeroOperacion::create([
            'serie' => 'B007',
            'correlativo' => '00000001',
            'tipo_comprobante_id' => 1,
            'punto_venta_id' => 7
        ]);
        NumeroOperacion::create([
            'serie' => 'F007',
            'correlativo' => '00000001',
            'tipo_comprobante_id' => 2,
            'punto_venta_id' => 7
        ]);
        NumeroOperacion::create([
            'serie' => 'ND07',
            'correlativo' => '00000001',
            'tipo_comprobante_id' => 3,
            'punto_venta_id' => 7
        ]);
        NumeroOperacion::create([
            'serie' => 'NC07',
            'correlativo' => '00000001',
            'tipo_comprobante_id' => 4,
            'punto_venta_id' => 7
        ]);

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'Mostrar Dashboard', 'guard_name' => 'sanctum'])->assignRole($superadmin);

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

        Permission::create(['name' => 'Listar Particulares', 'guard_name' => 'sanctum'])->assignRole($superadmin, $cajero);
        Permission::create(['name' => 'Crear Particulares', 'guard_name' => 'sanctum'])->assignRole($superadmin, $cajero);
        Permission::create(['name' => 'Mostrar Particulares', 'guard_name' => 'sanctum'])->assignRole($superadmin, $cajero);
        Permission::create(['name' => 'Editar Particulares', 'guard_name' => 'sanctum'])->assignRole($superadmin, $cajero);
        Permission::create(['name' => 'Eliminar Particulares', 'guard_name' => 'sanctum'])->assignRole($superadmin, $cajero);
        Permission::create(['name' => 'Restaurar Particulares', 'guard_name' => 'sanctum'])->assignRole($superadmin, $cajero);

        Permission::create(['name' => 'Listar Empresas', 'guard_name' => 'sanctum'])->assignRole($superadmin, $cajero);
        Permission::create(['name' => 'Crear Empresas', 'guard_name' => 'sanctum'])->assignRole($superadmin, $cajero);
        Permission::create(['name' => 'Mostrar Empresas', 'guard_name' => 'sanctum'])->assignRole($superadmin, $cajero);
        Permission::create(['name' => 'Editar Empresas', 'guard_name' => 'sanctum'])->assignRole($superadmin, $cajero);
        Permission::create(['name' => 'Eliminar Empresas', 'guard_name' => 'sanctum'])->assignRole($superadmin, $cajero);
        Permission::create(['name' => 'Restaurar Empresas', 'guard_name' => 'sanctum'])->assignRole($superadmin, $cajero);

        Permission::create(['name' => 'Listar Dependencias', 'guard_name' => 'sanctum'])->assignRole($superadmin, $cajero);
        // Permission::create(['name' => 'Crear Dependencias', 'guard_name' => 'sanctum'])->assignRole($superadmin, $cajero);
        Permission::create(['name' => 'Mostrar Dependencias', 'guard_name' => 'sanctum'])->assignRole($superadmin, $cajero);
        // Permission::create(['name' => 'Editar Dependencias', 'guard_name' => 'sanctum'])->assignRole($superadmin, $cajero);
        // Permission::create(['name' => 'Eliminar Dependencias', 'guard_name' => 'sanctum'])->assignRole($superadmin, $cajero);
        // Permission::create(['name' => 'Restaurar Dependencias', 'guard_name' => 'sanctum'])->assignRole($superadmin, $cajero);


        Permission::create(['name' => 'Listar Comprobantes', 'guard_name' => 'sanctum'])->assignRole($superadmin, $cajero);
        Permission::create(['name' => 'Crear Comprobantes', 'guard_name' => 'sanctum'])->assignRole($superadmin, $cajero);
        Permission::create(['name' => 'Mostrar Comprobantes', 'guard_name' => 'sanctum'])->assignRole($superadmin, $cajero);
        Permission::create(['name' => 'Anular Comprobantes', 'guard_name' => 'sanctum'])->assignRole($superadmin, $cajero);
        Permission::create(['name' => 'Restaurar Comprobantes', 'guard_name' => 'sanctum'])->assignRole($superadmin, $cajero);
        Permission::create(['name' => 'Mostrar Comprobantes Propios', 'guard_name' => 'sanctum'])->assignRole($superadmin, $cajero);
        Permission::create(['name' => 'Anular Comprobantes Propios', 'guard_name' => 'sanctum'])->assignRole($superadmin, $cajero);
        Permission::create(['name' => 'Restaurar Comprobantes Propios', 'guard_name' => 'sanctum'])->assignRole($superadmin, $cajero);

        Permission::create(['name' => 'Enviar-Boletas SUNAT', 'guard_name' => 'sanctum'])->assignRole($cajero);
        Permission::create(['name' => 'Enviar-Facturas SUNAT', 'guard_name' => 'sanctum'])->assignRole($cajero);

        Permission::create(['name' => 'Listar Puntos-Venta', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Crear Puntos-Venta', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Mostrar Puntos-Venta', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Editar Puntos-Venta', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Eliminar Puntos-Venta', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Restaurar Puntos-Venta', 'guard_name' => 'sanctum'])->assignRole($superadmin);

        Permission::create(['name' => 'Listar Números-Comprobante', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Crear Números-Comprobante', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Mostrar Números-Comprobante', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Editar Números-Comprobante', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Eliminar Números-Comprobante', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Restaurar Números-Comprobante', 'guard_name' => 'sanctum'])->assignRole($superadmin);

        Permission::create(['name' => 'Cajero Reportes-Periodo', 'guard_name' => 'sanctum'])->assignRole($superadmin, $cajero);
        Permission::create(['name' => 'Descuento Reportes-Periodo', 'guard_name' => 'sanctum'])->assignRole($superadmin, $cajero);
        Permission::create(['name' => 'Centro-Costo Reportes-Periodo', 'guard_name' => 'sanctum'])->assignRole($superadmin, $cajero);
        Permission::create(['name' => 'Recibo-Ingreso Reportes-Periodo', 'guard_name' => 'sanctum'])->assignRole($superadmin, $cajero);
        Permission::create(['name' => 'Consolidado Reportes-Periodo', 'guard_name' => 'sanctum'])->assignRole($superadmin, $cajero);
        Permission::create(['name' => 'Facturas Reportes-Periodo', 'guard_name' => 'sanctum'])->assignRole($superadmin, $cajero);
        Permission::create(['name' => 'Notas Reportes-Periodo', 'guard_name' => 'sanctum'])->assignRole($superadmin, $cajero);
    
    }
}
