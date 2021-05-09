<template>
    <app-layout>
        <div class="row justify-content-center mb-1">
            <fieldset class="col-12 col-md-6 px-3">
                <legend>Tipo de comprobante y usuario:</legend>
                <div class="row justify-content-center mb-2">      
                    <div class="col col-lg-5">
                        <b-form-select size="sm" v-model="selected" :options="options"></b-form-select>
                    </div>                         
                </div>                
                <div class="row justify-content-center">      
                    <div class="col col-lg-5">
                        <b-form-select size="sm" v-model="selected2" :options="options2"></b-form-select>
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
                            size="sm"
                            id="inline-form-custom-select-pref"
                            class="mb-2 mr-sm-2 mb-sm-0"
                            :options="[{ text: 'Elija una opción...', value: null }, 'CUI', 'Apellidos y Nombres']"
                            :value="null"
                        ></b-form-select>
                        <b-form-input
                            v-model="apn"
                            size="sm"
                            id="inline-form-input-name"
                            class="mb-2 mr-sm-2 mb-sm-0"
                            placeholder="Ingrese apellidos y nombres"
                            trim
                        ></b-form-input>
                        <b-button size="sm" variant="primary" @click="buscarUsuario">Buscar</b-button>
                    </b-form>
                </div>
            </fieldset>
        </div>       
        <div class="row justify-content-center mb-1" v-if="mostrar_usuarios">
            <fieldset class="col-12 col-md-6 px-3">
                <legend>Resultados de búsqueda:</legend>                   
                <usuarios :apn="apn" :key="renderKey"></usuarios>                
            </fieldset>
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
            apn: '',
            mostrar_usuarios: false,
            renderKey: 1,
            selected: null,
            selected2: null,
            options: [
                { value: null, text: 'Tipo de comprobante' },
                { value: 'BOLETA', text: 'BOLETA' },
                { value: 'FACTURA', text: 'FACTURA' },
                { value: 'NOTA_DEBITO', text: 'NOTA DE DÉBITO' },
                { value: 'NOTA_CREDITO', text: 'NOTA DE CRÉDITO' },
            ],
            options2: [
                { value: null, text: 'Tipo de usuario' },
                { value: 'ALUMNO', text: 'ALUMNO' },
                { value: 'PARTICULAR', text: 'PARTICULAR' },
                { value: 'DOCENTE', text: 'DOCENTE' },
                { value: 'DEPENDENCIA', text: 'DEPENDENCIA' },
            ]           
        };
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
</style>