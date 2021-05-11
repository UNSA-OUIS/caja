<template>
    <app-layout>  
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item ml-auto">
                    <inertia-link :href="route('dashboard')">Inicio</inertia-link>
                </li>
                <li class="breadcrumb-item">
                    <inertia-link :href="route('particulares.iniciar')">
                        Lista de particulares
                    </inertia-link>
                </li>
                <li class="breadcrumb-item active">
                    {{ accion }} particular
                </li>
            </ol>
        </nav>                   
        <div class="card">            
            <div class="card-header d-flex align-items-center">
                <span class="font-weight-bold">{{ accion }} particular</span>
            </div>
            <div class="card-body">                  
                <b-form @submit.prevent="enviar">
                    <b-row>
                        <b-col cols="6">
                            <b-form-group id="input-group-1" label="DNI:" label-for="input-1">
                                <b-form-input
                                    id="input-1"
                                    v-model="formData.dni"
                                    type="text"
                                    placeholder="Ingrese DNI" 
                                    :readonly="accion == 'Mostrar'"                           
                                ></b-form-input>
                                <div v-if="$page.props.errors.dni" class="text-danger">
                                    {{ $page.props.errors.dni[0] }}
                                </div>   
                            </b-form-group>
                        </b-col>
                        <b-col cols="6">
                            <b-form-group id="input-group-2" label="Email:" label-for="input-2">
                                <b-form-input
                                    id="input-2"
                                    v-model="formData.email"
                                    type="text"
                                    placeholder="Ingrese correo electrónico" 
                                    :readonly="accion == 'Mostrar'"                           
                                ></b-form-input>
                                <div v-if="$page.props.errors.email" class="text-danger">
                                    {{ $page.props.errors.email[0] }}
                                </div>   
                            </b-form-group>
                        </b-col>                        
                    </b-row>
                    <b-row>
                        <b-col cols="6">
                            <b-form-group id="input-group-3" label="Nombres:" label-for="input-3">
                                <b-form-input
                                    id="input-3"
                                    v-model="formData.nombres"
                                    type="text"
                                    placeholder="Ingrese Nombres" 
                                    :readonly="accion == 'Mostrar'"                           
                                ></b-form-input>
                                <div v-if="$page.props.errors.nombres" class="text-danger">
                                    {{ $page.props.errors.nombres[0] }}
                                </div>   
                            </b-form-group>
                        </b-col>
                        <b-col cols="6">
                            <b-form-group id="input-group-4" label="Apellidos:" label-for="input-4">
                                <b-form-input
                                    id="input-4"
                                    v-model="formData.apellidos"
                                    type="text"
                                    placeholder="Ingrese apellidos" 
                                    :readonly="accion == 'Mostrar'"                           
                                ></b-form-input>
                                <div v-if="$page.props.errors.apellidos" class="text-danger">
                                    {{ $page.props.errors.apellidos[0] }}
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
        name: "particulares.nuevo-mostrar",
        props: ["particular"],
        components: {
            AppLayout,                      
        },
        remember: 'formData',
        data() {
            return {       
                app_url: this.$root.app_url,             
                accion: '',
                formData: this.particular              
            }
        },       
        watch: {
            'formData.dni': async function (val) {                
                if (this.accion == 'Crear' && val.length == 8) { 
                    this.formData.nombres = ''
                    this.formData.apellidos = ''

                    try {
                        const response = await axios.get(`${this.app_url}/api_dni/${val}`)
                        if (response.data) {                                                                                                         
                                this.formData.nombres = response.data.nombres
                                let apPaterno = response.data.apellidoPaterno
                                let apMaterno = response.data.apellidoMaterno
                                this.formData.apellidos = `${apPaterno} ${apMaterno}` 
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
                this.$inertia.post(route('particulares.registrar'), this.formData)
            },       
            actualizar() {                
                this.$inertia.post(route('particulares.actualizar', [this.formData.id]), this.formData)                
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