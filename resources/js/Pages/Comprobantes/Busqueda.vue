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
                <li class="breadcrumb-item active">Buscar administrado</li>
            </ol>
        </nav>
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <span class="font-weight-bold">Buscar administrado</span>
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
                                        <option :value="null" disabled>Tipo de administrado...</option>
                                    </template>
                                </b-form-select>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="row justify-content-center mb-1" v-show="show_opciones_de_busqueda">
                    <fieldset class="col-12 col-md-6 px-3">
                        <legend>Opciones de búsqueda:</legend>
                        <div class="row justify-content-center">
                            <b-form inline v-on:submit.prevent="buscarUsuario">
                                <b-form-select
                                    v-model="opcion_busqueda"
                                    size="sm"
                                    id="inline-form-custom-select-pref"
                                    class="mb-2 mr-sm-2 mb-sm-0"
                                    :options="opciones_busqueda"
                                    :value="null"
                                >
                                    <template v-slot:first>
                                        <option :value="null" disabled>Elija una opción...</option>
                                    </template>
                                </b-form-select>
                                <b-form-input
                                    v-model="filtro"
                                    :state="validation"
                                    size="sm"
                                    id="inline-form-input-name"
                                    class="mb-2 mr-sm-2 mb-sm-0"
                                    trim
                                ></b-form-input>
                                <b-button size="sm" variant="primary" @click="buscarUsuario">Buscar</b-button>
                                <!--<b-form-invalid-feedback :state="validation">
                                    {{this.form_validation_variables.info_message}}
                                </b-form-invalid-feedback>  -->
                            </b-form>
                            <div class="container">
                            <b-alert :show="validation==false" variant="danger">{{this.form_validation_variables.info_message}}</b-alert>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="row justify-content-center mb-1" v-show="show_busqueda_documento">
                    <fieldset class="col-12 col-md-6 px-3">
                        <legend>Comprobante a modificar:</legend>
                        <div class="row justify-content-center">
                            <b-form inline v-on:submit.prevent="buscarComprobante">
                                <b-form-input
                                    v-model="serie"
                                    :state="validation"
                                    placeholder="Serie"
                                    size="sm"
                                    id="inline-form-input-serie"
                                    class="mb-2 mr-sm-2 mb-sm-0"
                                    trim
                                ></b-form-input>
                                -
                                <b-form-input
                                    v-model="correlativo"
                                    :state="validation"
                                    placeholder="Correlativo"
                                    size="sm"
                                    id="inline-form-input-correlativo"
                                    class="mb-2 mr-sm-2 mb-sm-0"
                                    trim
                                ></b-form-input>
                                <b-button size="sm" variant="primary" @click="buscarComprobante">Buscar</b-button>
                            </b-form>
                        </div>
                    </fieldset>
                </div>
                <div class="row justify-content-center mb-1" v-if="mostrar_usuarios">
                    <fieldset class="col-12 col-md-6 px-3">
                        <legend>Resultados de búsqueda:</legend>
                        <comprobantes
                            v-if="tipo_comprobante === 'NOTA DE DÉBITO' || tipo_comprobante === 'NOTA DE CRÉDITO'"
                            :tipo_comprobante="tipo_comprobante"
                            :serie="serie"
                            :correlativo="correlativo"
                            :key="renderKey"
                        >
                        </comprobantes>
                        <alumnos
                            v-if="tipo_usuario === 'ALUMNO'"
                            :opcion_busqueda="opcion_busqueda"
                            :filtro="filtro"
                            :key="renderKey"
                        >
                        </alumnos>
                        <particulares
                            v-else-if="tipo_usuario === 'PARTICULAR'"
                            :opcion_busqueda="opcion_busqueda"
                            :filtro="filtro"
                            :key="renderKey"
                        >
                        </particulares>
                        <empresas
                            v-else-if="tipo_usuario === 'EMPRESA'"
                            :opcion_busqueda="opcion_busqueda"
                            :filtro="filtro"
                            :key="renderKey"
                        >
                        </empresas>
                        <docentes
                            v-else-if="tipo_usuario === 'DOCENTE'"
                            :opcion_busqueda="opcion_busqueda"
                            :filtro="filtro"
                            :key="renderKey"
                        >
                        </docentes>
                        <dependencias
                            v-else-if="tipo_usuario === 'DEPENDENCIA'"
                            :opcion_busqueda="opcion_busqueda"
                            :filtro="filtro"
                            :key="renderKey"
                        >
                        </dependencias>
                    </fieldset>
                </div>
            </div>
        </div>
    </app-layout>
</template>
<script>
const axios = require("axios");

import AppLayout from "@/Layouts/AppLayout";
import Alumnos from "./Alumnos";
import Particulares from "./Particulares";
import Empresas from "./Empresas";
import Docentes from "./Docentes";
import Dependencias from "./Dependencias";
import Comprobantes from './Comprobantes.vue';

export default {
    name: "cobros.busqueda",
    components: {
        AppLayout,
        Alumnos,
        Docentes,
        Dependencias,
        Particulares,
        Empresas,
        Comprobantes
    },
    data() {
        return {
            app_url: this.$root.app_url,
            tipo_comprobante: null,
            tipo_usuario: null,
            opcion_busqueda: null,
            filtro: '',
            serie: '',
            correlativo: '',
            tipos_comprobante: [
                { value: 'BOLETA', text: 'BOLETA' },
                { value: 'FACTURA', text: 'FACTURA' },
                { value: 'NOTA DE DÉBITO', text: 'NOTA DE DÉBITO' },
                { value: 'NOTA DE CRÉDITO', text: 'NOTA DE CRÉDITO' },
            ],
            tipos_usuario: [],
            tipos_usuario_boleta: [
                { value: 'ALUMNO', text: 'ALUMNO' },
                { value: 'PARTICULAR', text: 'PARTICULAR' },
                { value: 'DOCENTE', text: 'DOCENTE' },
                { value: 'DEPENDENCIA', text: 'DEPENDENCIA' },
            ],
            tipos_usuario_factura: [
                { value: 'EMPRESA', text: 'EMPRESA' },
            ],
            opciones_busqueda_alumno: [
                { value: 'CUI', text: 'CUI' },
                { value: 'APN', text: 'Apellidos y nombres' },
            ],
            opciones_busqueda_particular: [
                { value: 'DNI', text: 'DNI' },
                { value: 'APN', text: 'Apellidos y nombres' },
            ],
            opciones_busqueda_docente: [
                { value: 'CODIGO', text: 'CÓDIGO' },
                { value: 'APN', text: 'Apellidos y nombres' },
            ],
            opciones_busqueda_dependencia: [
                { value: 'CODIGO', text: 'CÓDIGO' },
                { value: 'NOMBRE', text: 'Nombre dependencia' },
            ],
            opciones_busqueda_empresa: [
                { value: 'RUC', text: 'RUC' },
                { value: 'RAZON_SOCIAL', text: 'Razón social' },
            ],
            form_validation_variables: {
                minsize: 0,
                maxsize: 99,
                only_numbers: false,
                only_letters: false,
                info_message:'',
            },
            opciones_busqueda: [],
            show_select_tipos_usuario: false,
            show_opciones_de_busqueda: false,
            show_busqueda_documento: false,
            mostrar_usuarios: false,
            renderKey: 1,
        };
    },
    computed: {
      validation() {
        var regLetters = /^[a-zA-Z\s]*$/;
        if(this.filtro.length=='')return null;
        else if(this.form_validation_variables.only_numbers && isNaN(Number(this.filtro))){
            this.form_validation_variables.info_message="Debe ingresar sólo números."
        }
        else if(this.form_validation_variables.only_letters && !regLetters.test(this.filtro)){
            this.form_validation_variables.info_message="Debe ingresar sólo letras."
        }
        else if(!(this.filtro.length >= this.form_validation_variables.minsize && this.filtro.length <= this.form_validation_variables.maxsize)){
            if(this.form_validation_variables.minsize == this.form_validation_variables.maxsize){
                this.form_validation_variables.info_message = "Debe tener exactamente " + this.form_validation_variables.minsize + " dígitos."
            }else
                this.form_validation_variables.info_message = "Debe ingresar al  menos " + this.form_validation_variables.minsize + " caracteres."
        }else{
            this.form_validation_variables.info_message = ''
            return true
        }
        return false
      }
    },
    watch: {
        tipo_comprobante: function(val) {
            this.tipo_usuario = null
            this.opciones_busqueda = null
            if (val === 'BOLETA') {
                this.tipos_usuario = this.tipos_usuario_boleta
                this.show_select_tipos_usuario = true
                this.show_busqueda_documento = false
            }
            else if (val === 'FACTURA') {
                this.tipos_usuario = this.tipos_usuario_factura
                this.show_select_tipos_usuario = true
                this.show_busqueda_documento = false
            }
            else {
                this.show_select_tipos_usuario = false
                this.show_busqueda_documento = true
            }
        },
        tipo_usuario: function(val) {
            this.opcion_busqueda = null
            this.filtro = ''
            this.mostrar_usuarios = false

            if (val === 'ALUMNO') {
                this.show_opciones_de_busqueda = true
                this.opciones_busqueda = this.opciones_busqueda_alumno
            }
            else if (val === 'PARTICULAR') {
                this.show_opciones_de_busqueda = true
                this.opciones_busqueda = this.opciones_busqueda_particular
            }
            else if (val === 'EMPRESA') {
                this.show_opciones_de_busqueda = true
                this.opciones_busqueda = this.opciones_busqueda_empresa
            }
            else if (val === 'DOCENTE') {
                this.show_opciones_de_busqueda = true
                this.opciones_busqueda = this.opciones_busqueda_docente
            }
            else if (val === 'DEPENDENCIA') {
                this.show_opciones_de_busqueda = true
                this.opciones_busqueda = this.opciones_busqueda_dependencia
            }
            else {
                this.show_opciones_de_busqueda = false
            }

        },
        opcion_busqueda: function (val){

            switch (val) {
                case 'DNI':
                    this.filtro = ''
                    this.form_validation_variables.minsize=8;
                    this.form_validation_variables.maxsize=8;
                    this.form_validation_variables.only_numbers=true;
                    this.form_validation_variables.only_letters=false;
                    break;
                case 'CUI':
                    this.filtro = ''
                    this.form_validation_variables.minsize=8;
                    this.form_validation_variables.maxsize=8;
                    this.form_validation_variables.only_letters=false;
                    this.form_validation_variables.only_numbers=true;
                    break;
                case 'RUC':
                    this.filtro = ''
                    this.form_validation_variables.minsize=11;
                    this.form_validation_variables.maxsize=11;
                    this.form_validation_variables.only_letters=false;
                    this.form_validation_variables.only_numbers=true;
                    break;
                case 'RAZON_SOCIAL':
                    this.filtro = ''
                    this.form_validation_variables.minsize=1;
                    this.form_validation_variables.maxsize=99;
                    this.form_validation_variables.only_letters=false;
                    this.form_validation_variables.only_numbers=false;
                    break;
                case 'NOMBRE':
                    this.filtro = ''
                    this.form_validation_variables.minsize=8;
                    this.form_validation_variables.maxsize=150;
                    this.form_validation_variables.only_letters=false;
                    this.form_validation_variables.only_numbers=false;
                    break;
                case 'CODIGO':
                    this.filtro = ''
                    this.form_validation_variables.minsize=4;
                    this.form_validation_variables.maxsize=10;
                    this.form_validation_variables.only_letters=false;
                    this.form_validation_variables.only_numbers=false;
                    break;
                case 'APN':
                    this.filtro = ''
                    this.form_validation_variables.minsize=2;
                    this.form_validation_variables.maxsize=99;
                    this.form_validation_variables.only_letters=true;
                    this.form_validation_variables.only_numbers=false;
                    break;
                default:
                    break;
            }
        }
    },
    methods: {
        buscarUsuario() {
            if(this.validation){
                this.mostrar_usuarios = true
                this.renderKey++
            }
        },
        buscarComprobante() {
            /*this.$inertia.get(route('comprobantes.nota'), {
                'serie' : this.serie,
                'correlativo': this.correlativo,
                'tipo_comprobante': this.tipo_comprobante,
            })*/
            this.mostrar_usuarios = true
            this.renderKey++
        },
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
