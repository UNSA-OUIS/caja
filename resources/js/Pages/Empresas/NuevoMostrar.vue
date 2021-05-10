<template>
    <app-layout>    
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item ml-auto">
                    <inertia-link :href="route('dashboard')">Inicio</inertia-link>
                </li>
                <li class="breadcrumb-item">
                    <inertia-link :href="route('empresas.iniciar')">
                        Lista de empresas
                    </inertia-link>
                </li>
                <li class="breadcrumb-item active">
                    {{ accion }} empresa
                </li>
            </ol>
        </nav>                 
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <span class="font-weight-bold">{{ accion }} empresa</span>
            </div>                  
            <div class="card-body">                  
                <b-form @submit.prevent="enviar">
                    <b-row>
                        <b-col cols="6">
                            <b-form-group id="input-group-1" label="RUC:" label-for="input-1">
                                <b-form-input
                                    id="input-1"
                                    v-model="formData.ruc"
                                    type="text"
                                    placeholder="Ingrese RUC" 
                                    :readonly="accion == 'Mostrar'"                           
                                ></b-form-input>
                                <div v-if="$page.props.errors.ruc" class="text-danger">
                                    {{ $page.props.errors.ruc[0] }}
                                </div>   
                            </b-form-group>
                        </b-col>
                        <b-col cols="6">
                            <b-form-group id="input-group-2" label="Razón social:" label-for="input-2">
                                <b-form-input
                                    id="input-2"
                                    v-model="formData.razon_social"
                                    type="text"
                                    placeholder="Ingrese razón social" 
                                    :readonly="accion == 'Mostrar'"                           
                                ></b-form-input>
                                <div v-if="$page.props.errors.razon_social" class="text-danger">
                                    {{ $page.props.errors.razon_social[0] }}
                                </div>   
                            </b-form-group>
                        </b-col>                        
                    </b-row>
                    <b-row>
                        <b-col cols="6">
                            <b-form-group id="input-group-3" label="Email:" label-for="input-3">
                                <b-form-input
                                    id="input-3"
                                    v-model="formData.email"
                                    type="text"
                                    placeholder="Ingrese correo eletrónico" 
                                    :readonly="accion == 'Mostrar'"                           
                                ></b-form-input>
                                <div v-if="$page.props.errors.email" class="text-danger">
                                    {{ $page.props.errors.email[0] }}
                                </div>   
                            </b-form-group>
                        </b-col>
                        <b-col cols="6">
                            <b-form-group id="input-group-4" label="Dirección:" label-for="input-4">
                                <b-form-input
                                    id="input-4"
                                    v-model="formData.direccion"
                                    type="text"
                                    placeholder="Ingrese dirección" 
                                    :readonly="accion == 'Mostrar'"                           
                                ></b-form-input>
                                <div v-if="$page.props.errors.direccion" class="text-danger">
                                    {{ $page.props.errors.direccion[0] }}
                                </div>   
                            </b-form-group>
                        </b-col>                        
                    </b-row>                                 
                    <b-button v-if="accion == 'Crear'" type="submit" variant="success">Registrar</b-button>               
                    <b-button v-else-if="accion == 'Mostrar'" type="submit" variant="warning">Editar</b-button>     
                    <b-button v-else-if="accion == 'Editar'" type="submit" variant="success">Actualizar</b-button>
                </b-form>                                
            </div>
        </div>            
    </app-layout>
</template>

<script>    
    const axios = require('axios')
    import AppLayout from '@/Layouts/AppLayout'    

    export default {
        name: "empresas.nuevo-mostrar",
        props: ["empresa"],
        components: {
            AppLayout,                      
        },
        remember: 'formData',
        data() {
            return {        
                app_url: this.$root.app_url,        
                accion: '',
                formData: this.empresa
            }
        },    
        watch: {
            'formData.ruc': async function (val) {                
                if (this.accion == 'Crear' && val.length == 11) { 
                    this.formData.razon_social = ''
                    this.formData.direccion = ''

                    try {
                        const response = await axios.get(`${this.app_url}/api_ruc/${val}`)
                        if (response.data) {                                                                                                         
                                this.formData.razon_social = response.data.razonSocial
                                this.formData.direccion = response.data.direccion
                        }
                    } catch (error) {
                        console.log(error)
                    }                                    
                }                
            }
        },   
        created() {                        
            if (!this.formData.id) {               
                this.accion = 'Crear'
            }
            else {                
                this.accion = 'Mostrar'
            }            
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
            registrar() {                                         
                this.$inertia.post(route('empresas.registrar'), this.formData)
            },       
            actualizar() {                
                this.$inertia.post(route('empresas.actualizar', [this.formData.id]), this.formData)
            }            
        },
    }
</script>
<style scoped>
    .breadcrumb li a {
        color: blue;
    }
    .breadcrumb {
        margin-bottom: 0;
        background-color: white;
    }
</style>