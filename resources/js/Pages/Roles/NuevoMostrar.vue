<template>
    <app-layout>
        <div class="card">
            <div class="card-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <inertia-link :href="route('dashboard')"
                            >Inicio</inertia-link
                        >
                    </li>
                    <li class="breadcrumb-item">
                        <inertia-link :href="route('roles.iniciar')"
                            >Lista de roles</inertia-link
                        >
                    </li>
                    <li class="breadcrumb-item active">
                        {{ accion }} rol
                    </li>
                </ol>
            </div>
            <div class="card-body">
                <b-form>
                    <b-form-group
                        id="input-group-1"
                        label="Nombre:"
                        label-for="input-1"
                    >
                        <b-form-input
                            id="input-1"
                            v-model="rol.name"
                            placeholder="Nombre de unidad de medida"
                            :readonly="accion == 'Mostrar'"
                        ></b-form-input>
                        <div
                            v-if="$page.props.errors.name"
                            class="text-danger"
                        >
                            {{ $page.props.errors.name[0] }}
                        </div>
                    </b-form-group>
                    <hr>   
                    <h2 class="h4 mb-1">Permisos</h2>
                    <p class="small text-muted font-italic mb-4">Asignación de permisos por rol.</p>
                    <b-table bordered striped hover :items="categoria_permisos" :fields="fields">
                        <template v-slot:cell(permisos)="row">
                            <div class="custom-control custom-checkbox custom-control-inline" v-for="permiso in row.item.permisos" :key="permiso.id">
                                <input 
                                    class="custom-control-input" 
                                    :id="permiso.id" 
                                    type="checkbox"
                                    v-model="rol.permisos_seleccionados"                        
                                    :value="permiso.id"
                                    :disabled="accion == 'Mostrar'"
                                >
                                <label class="cursor-pointer font-italic d-block custom-control-label" :for="permiso.id">{{ permiso.nombre }}</label>
                            </div>                           
                        </template>
                    </b-table>
                    <div
                        v-if="$page.props.errors.permisos_seleccionados"
                        class="text-danger"
                    >
                        {{ $page.props.errors.permisos_seleccionados[0] }}
                    </div>
                    <hr>
                    <b-button
                        v-if="accion == 'Crear'"
                        @click="registrar"
                        variant="success"
                        >Registrar</b-button
                    >
                    <b-button
                        v-else-if="accion == 'Mostrar'"
                        @click="accion = 'Editar'"
                        variant="warning"
                        >Editar</b-button
                    >
                    <b-button
                        v-else-if="accion == 'Editar'"
                        @click="actualizar"
                        variant="success"
                        >Actualizar</b-button
                    >
                </b-form>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";

export default {
    name: "roles.mostrar",
    props: ["rol", "permissions"],
    components: {
        AppLayout
    },
    data() {
        return {
            accion: "",
            fields: [                
                { key: "categoria", label: "Menú", sortable: true },
                { key: "permisos", label: "Permisos" },                
            ],
            categoria_permisos: [],       
        };
    },
    created() {
        if (!this.rol.id) {
            this.accion = "Crear";
        } else {
            this.accion = "Mostrar";
        }
        this.mostrar_permisos()
    },
    methods: {
        mostrar_permisos() {
            if (this.permissions.length > 0) {                            
                let permisos = []            
                let categoria_anterior = this.permissions[0].name.split(" ").pop()
                let categoria, permiso_id, permiso_nombre

                this.permissions.forEach(permission => {
                    categoria = permission.name.split(" ").pop()
                    permiso_id = permission.id
                    permiso_nombre = permission.name.split(" ").shift()
                    
                    if (categoria !== categoria_anterior) {                                                    
                        this.categoria_permisos.push({
                            'categoria': categoria_anterior,
                            'permisos': permisos
                        })
                        permisos = []
                    }

                    categoria_anterior = categoria
                    permisos.push({
                        'id': permiso_id,
                        'nombre': permiso_nombre
                    })               
                })

                this.categoria_permisos.push({
                    'categoria': categoria_anterior,
                    'permisos': permisos
                })                           
            }            
        },
        registrar() {
            this.$inertia.post(
                route("roles.registrar"),
                this.rol
            );
        },
        actualizar() {
            this.$inertia.post(
                route("roles.actualizar", [this.rol.id]),
                this.rol
            );
        }
    }
};
</script>
