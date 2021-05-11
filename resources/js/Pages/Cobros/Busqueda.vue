<template>
    <app-layout>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item ml-auto">                    
                    <inertia-link :href="route('dashboard')">Inicio</inertia-link>
                </li>
                 <li class="breadcrumb-item">
                    <inertia-link :href="route('cobros.iniciar')">
                        Lista de cobros
                    </inertia-link>
                </li>                
                <li class="breadcrumb-item active">Buscar usuario</li>
            </ol>
        </nav> 
        <div class="card">
            <div class="card-header d-flex align-items-center">                
                <span class="font-weight-bold">Buscar usuario</span>                
            </div>                   
            <div class="card-body">
                <div class="row justify-content-center mb-1">
                    <fieldset class="col-12 col-md-6 px-3">
                        <legend>Opciones de comprobante:</legend>
                        <div class="row justify-content-center mb-2">      
                            <div class="col col-lg-5">
                                <b-form-select size="sm" v-model="tipo_comprobante" :options="tipos_comprobante">
                                    <template v-slot:first>
                                        <option :value="null" disabled>Tipo de comprobante...</option>
                                    </template>                            
                                </b-form-select>
                            </div>                         
                        </div>                
                        <div class="row justify-content-center" v-show="show_select_tipos_usuario">      
                            <div class="col col-lg-5">
                                <b-form-select size="sm" v-model="tipo_usuario" :options="tipos_usuario">
                                    <template v-slot:first>
                                        <option :value="null" disabled>Tipo de usuario...</option>
                                    </template>                            
                                </b-form-select>
                            </div>                    
                        </div>
                    </fieldset>
                </div>
                <div class="row justify-content-center mb-1">
                    <fieldset class="col-12 col-md-6 px-3">
                        <legend>Opciones de búsqueda:</legend>
                        <div class="row justify-content-center">      
                            <b-form inline>                        
                                <b-form-select
                                    v-model="opcion_busqueda"
                                    size="sm"
                                    id="inline-form-custom-select-pref"
                                    class="mb-2 mr-sm-2 mb-sm-0"
                                    :options="opciones_busqueda"
                                    :value="null"
                                ></b-form-select>
                                <b-form-input
                                    v-model="filtro"
                                    size="sm"
                                    id="inline-form-input-name"
                                    class="mb-2 mr-sm-2 mb-sm-0"                                    
                                    trim
                                ></b-form-input>
                                <b-button size="sm" variant="primary" @click="buscarUsuario">Buscar</b-button>
                            </b-form>
                        </div>
                    </fieldset>
                </div>       
                <div class="row justify-content-center mb-1">
                    <fieldset class="col-12 col-md-6 px-3">
                        <legend>Resultados de búsqueda:</legend>                   
                        <usuarios :opcion_busqueda="opcion_busqueda" :filtro="filtro" :key="renderKey"></usuarios>                
                    </fieldset>
                </div>   
            </div>
        </div>    
    </app-layout>    
</template>
<script>
import AppLayout from "@/Layouts/AppLayout";
import Usuarios from "./Usuarios";

export default {
    name: "cobros.busqueda",
    components: {
        AppLayout,        
        Usuarios        
    },
    data() {
        return {
            app_url: this.$root.app_url, 
            filtro: '',            
            renderKey: 1,
            opcion_busqueda: 'CUI',
            tipo_comprobante: null,
            tipo_usuario: null,
            tipos_comprobante: [                
                { value: 'BOLETA', text: 'BOLETA' },
                { value: 'FACTURA', text: 'FACTURA' },
                { value: 'NOTA_DEBITO', text: 'NOTA DE DÉBITO' },
                { value: 'NOTA_CREDITO', text: 'NOTA DE CRÉDITO' },
            ],
            tipos_usuario: [],            
            tipos_usuario_boleta: [                
                { value: 'ALUMNO', text: 'ALUMNO' },
                { value: 'PARTICULAR', text: 'PARTICULAR' },
                { value: 'DOCENTE', text: 'DOCENTE' },
                { value: 'DEPENDENCIA', text: 'DEPENDENCIA' },
            ],           
            tipos_usuario_factura: [                
                { value: 'EMPRESA', text: 'EMRESA' },                
            ],
            opciones_busqueda: [                
                { value: 'CUI', text: 'CUI' },                
                { value: 'APN', text: 'Apellidos y Nombres' },                
            ],
            show_select_tipos_usuario: false         
        };
    },    
    watch: {
        tipo_comprobante: function(val) {            
            this.tipo_usuario = null

            if (val === 'BOLETA') {
                this.tipos_usuario = this.tipos_usuario_boleta
                this.show_select_tipos_usuario = true
            }
            else if (val === 'FACTURA') {
                this.tipos_usuario = this.tipos_usuario_factura
                this.show_select_tipos_usuario = true
            }     
            else {
                this.show_select_tipos_usuario = false
            }
        }      
    },
    methods: {
        buscarUsuario() {
            this.mostrar_usuarios = true
            this.renderKey++
        }
    },
};
</script>
<style scoped>
    fieldset {    
        border-radius: 4px;
        border: 1px solid #ddd;    
        background-color: #fff;    
        padding-bottom: 10px;
        height: auto;
    }

    legend {        
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 4px;    
        font-size: 14px;    
        font-weight: 600;
        padding: 3px 5px 3px 7px;
        width: auto;
    }

    .breadcrumb li a {
            color: blue;
    }

    .breadcrumb {
        margin-bottom: 0;
        background-color: white;
    }
</style>
