<template>
    <app-layout>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item ml-auto">
                    <inertia-link :href="route('dashboard')">Inicio</inertia-link>
                </li>
                <li class="breadcrumb-item">
                    <inertia-link :href="route('unidades-medida.iniciar')">
                        Lista de roles
                    </inertia-link>
                </li>
                <li class="breadcrumb-item active">
                    {{ accion }} rol
                </li>
            </ol>
        </nav>
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <span class="font-weight-bold">{{ accion }} rol</span>
            </div>
            <div class="card-body">
                <b-form @submit.prevent="enviar">
                    <b-form-group
                        id="input-group-1"
                        label="Nombre:"
                        label-for="input-1"
                    >
                        <b-form-input
                            id="input-1"
                            v-model="formData.name"
                            placeholder="Nombre de rol"
                            :readonly="accion == 'Mostrar'"
                        ></b-form-input>
                        <div
                            v-if="$page.props.errors.name"
                            class="text-danger"
                        >
                            {{ $page.props.errors.name[0]}}
                        </div>
                    </b-form-group>
                    <hr>
                    <h2 class="h4 mb-1">Permisos</h2>
                    <p class="small text-muted font-italic mb-4">Asignación de permisos por rol.</p>
                    <b-table bordered striped hover :items="categoria_permisos" :fields="fields">
                        <template v-slot:cell(permisos)="row">
                            <div style="width:150px" class="custom-control custom-checkbox custom-control-inline" v-for="permiso in row.item.permisos" :key="permiso.id">
                                <input
                                    class="custom-control-input"
                                    :id="permiso.id"
                                    type="checkbox"
                                    v-model="formData.permisos_seleccionados"
                                    :value="permiso.id"
                                    @change="loadSelectAll(row.index)"
                                    :disabled="accion == 'Mostrar'"
                                >
                                <label class="cursor-pointer font-italic d-block custom-control-label" :for="permiso.id">{{ permiso.nombre }}</label>
                            </div>
                        </template>
                        <!-- --------------- selecionar todo ------------ -->
                        <template v-slot:cell(acciones)=" select_row">
                            <div class="custom-control custom-checkbox custom-control-inline" >
                                <input
                                    class="custom-control-input"
                                    type="checkbox"
                                    :id=" select_row.index+total_permisos+1"
                                    v-model="bool_allSelecteds"
                                    :value=" select_row.index"
                                    :disabled="accion == 'Mostrar'"
                                    @click="selectAll( select_row.index,$event)"
                                >
                                <label class="cursor-pointer font-italic d-block custom-control-label" :for=" select_row.index+total_permisos+1">{{  select_row.item.acciones }}</label>
                            </div>
                        </template>
                        <!-- --------------- /selecionar todo ------------ -->
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
    remember: 'formData',
    data() {
        return {
            accion: "",
            fields: [
                { key: "categoria", label: "Menú", sortable: true, tdClass: "categoria" },
                { key: "permisos",stickyColumn: true, label: "Permisos" },
                { key: "acciones", label: "Seleccionar" },
            ],
            formData:this.rol,
            categoria_permisos: [],
            bool_allSelecteds: [],
            total_permisos: this.permissions.length
        };
    },
    created() {
        if (!this.rol.id) {
            this.accion = "Crear";
        } else {
            this.accion = "Mostrar";

        }
        this.mostrar_permisos()
        this.loadSelectAll()

    },
    methods: {
        enviar() {
            if (this.accion == 'Crear') {
                this.registrar()
            }
            else if (this.accion == 'Mostrar') {
                this.accion = 'Editar'
            }
            else if (this.accion == 'Editar') {
                this.actualizar()
            }
        },
        mostrar_permisos() {
            if (this.permissions.length > 0) {
                let permisos = []
                let categoria_anterior = this.permissions[0].name.split(" ").pop()
                let categoria, permiso_id, permiso_nombre
                let counter_index=0

                this.permissions.forEach(permission => {
                    permiso_id = permission.id

                    if (permission.name.split(" ").length == 2) {
                        categoria = permission.name.split(" ").pop()
                        permiso_nombre = permission.name.split(" ").shift()
                    }
                    else if (permission.name.split(" ").length == 3) {
                        categoria = permission.name.split(" ")[1]
                        permiso_nombre = permission.name.split(" ")[0] + " " + permission.name.split(" ")[2]
                    }

                    if (categoria !== categoria_anterior) {

                        this.categoria_permisos.push({
                            'categoria': categoria_anterior,
                            'permisos': permisos,
                            'acciones': "Todo"
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
                    'permisos': permisos,
                    'acciones': "Todo"

                })
            }
        },
        registrar() {
            this.$inertia.post(
                route("roles.registrar"),
                this.formData
            );
        },
        actualizar() {
            this.$inertia.post(
                route("roles.actualizar", [this.formData.id]),
                this.formData
            );
        },
        selectAll(val,e){
            this.categoria_permisos[val].permisos.forEach(permiso=>{
                if(e.target.checked){//if is true add to permissions array

                    if(!this.formData.permisos_seleccionados.includes(permiso.id)){
                        this.formData.permisos_seleccionados.push(permiso.id)
                    }
                }else{
                    const index = this.formData.permisos_seleccionados.indexOf(permiso.id);
                    if (index > -1) {
                    this.formData.permisos_seleccionados.splice(index, 1);
                    }
                }
            })

        },
        loadSelectAll(category){
            if(category==undefined && this.formData.permisos_seleccionados.length){
                let cat_id=0;
                this.categoria_permisos.forEach(categoria=>{
                    let counter_index=0
                    categoria.permisos.forEach(permiso=>{
                        if(this.formData.permisos_seleccionados.includes(permiso.id)){
                            counter_index+=1;
                        }

                    });
                    if(counter_index == categoria.permisos.length){
                        if(!this.bool_allSelecteds.includes(cat_id))
                            this.bool_allSelecteds.push(cat_id)
                    }
                    cat_id+=1;
                })
            }else if(category!=undefined){
                let counter_index=0
                this.categoria_permisos[category].permisos.forEach(permiso=>{
                    if(this.formData.permisos_seleccionados.includes(permiso.id)){
                    counter_index+=1;
                    }

                });
                if(counter_index== this.categoria_permisos[category].permisos.length){
                    if(!this.bool_allSelecteds.includes(category))
                        this.bool_allSelecteds.push(category)
                }else{
                    const index = this.bool_allSelecteds.indexOf(category);
                    if (index > -1)this.bool_allSelecteds.splice(index, 1);
                }
            }
        }
    }
};
</script>
<style scoped>
    .categoria {
        width: 175px;
        font-weight: bold;
    }
    .breadcrumb li a {
        color: blue;
    }
    .breadcrumb {
        margin-bottom: 0;
        background-color: white;
    }
</style>
