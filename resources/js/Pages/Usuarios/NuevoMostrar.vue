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
                        <inertia-link :href="route('usuarios.iniciar')"
                            >Lista de usuarios</inertia-link
                        >
                    </li>
                    <li class="breadcrumb-item active">
                        {{ accion }} usuario
                    </li>
                </ol>
            </div>
            <div class="card-body">
                <b-form>
                    <b-row>
                        <b-col>
                            <b-form-group
                                id="input-group-1"
                                label="Nombre:"
                                label-for="input-1"
                            >
                                <b-form-input
                                    id="input-1"
                                    v-model="usuario.name"
                                    placeholder="Nombre de usuario"
                                    :readonly="accion == 'Mostrar'"
                                ></b-form-input>
                                <div
                                    v-if="$page.props.errors.name"
                                    class="text-danger"
                                >
                                    {{ $page.props.errors.name[0] }}
                                </div>
                            </b-form-group>
                        </b-col>
                        <b-col>
                            <b-form-group
                                id="input-group-2"
                                label="Email:"
                                label-for="input-2"
                            >
                                <b-form-input
                                    id="input-2"
                                    v-model="usuario.email"
                                    placeholder="Correo eletrónico"
                                    :readonly="accion == 'Mostrar'"
                                ></b-form-input>
                                <div
                                    v-if="$page.props.errors.email"
                                    class="text-danger"
                                >
                                    {{ $page.props.errors.email[0] }}
                                </div>
                            </b-form-group>
                        </b-col>                                              
                    </b-row>
                    <b-row>
                        <b-col>
                            <b-form-group
                                id="input-group-3"
                                label="Contraseña:"
                                label-for="input-3"
                            >
                                <b-form-input
                                    id="input-3"
                                    v-model="usuario.password"
                                    placeholder="Contraseña"
                                    :readonly="accion == 'Mostrar'"
                                ></b-form-input>
                                <div
                                    v-if="$page.props.errors.password"
                                    class="text-danger"
                                >
                                    {{ $page.props.errors.password[0] }}
                                </div>
                            </b-form-group>
                        </b-col>
                        <b-col>
                            <b-form-group id="input-group-4" label="Rol (es):" label-for="input-4">
                                <b-form-select
                                    id="input-4"
                                    v-model="usuario.roles_seleccionados"
                                    :options="roles"              
                                    multiple 
                                    :select-size="4"
                                    :disabled="accion == 'Mostrar'"
                                >              
                                </b-form-select>
                                <div v-if="$page.props.errors.roles_seleccionados" class="text-danger">
                                    {{ $page.props.errors.roles_seleccionados[0] }}
                                </div>
                            </b-form-group>    
                        </b-col>                        
                    </b-row>   
                    <template v-if="permissions">
                        <hr>   
                        <h2 class="h4 mb-1">Permisos</h2>
                        <p class="small text-muted font-italic mb-4">Asignación de permisos por usuario.</p>
                        <b-table bordered striped hover :items="categoria_permisos" :fields="fields">
                            <template v-slot:cell(permisos)="row">
                                <div class="custom-control custom-checkbox custom-control-inline" v-for="permiso in row.item.permisos" :key="permiso.id">
                                    <input 
                                        class="custom-control-input" 
                                        :id="permiso.id" 
                                        type="checkbox"
                                        v-model="usuario.permisos_seleccionados"                        
                                        :value="permiso.id"
                                        :disabled="accion == 'Mostrar'"
                                    >
                                    <label class="cursor-pointer font-italic d-block custom-control-label" :for="permiso.id">{{ permiso.nombre }}</label>
                                </div>                           
                            </template>
                        </b-table>
                        <hr>                                                                                      
                    </template>                                    
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
    name: "usuarios.mostrar",
    props: ["usuario", "roles", "permissions"],
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
        if (!this.usuario.id) {
            this.accion = "Crear"
        } else {            
            this.accion = "Mostrar"
            this.mostrar_permisos()
        }
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
                route("usuarios.registrar"),
                this.usuario
            );
        },
        actualizar() {
            this.$inertia.post(
                route("usuarios.actualizar", [this.usuario.id]),
                this.usuario
            );
        }
    }
};
</script>
