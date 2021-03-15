<template>
    <app-layout>                    
        <div class="card">
            <div class="card-header">                
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><inertia-link :href="`${app_url}/dashboard`">Inicio</inertia-link></li>                    
                    <li class="breadcrumb-item"><inertia-link :href="route('accesos-google.iniciar')">Lista de Accesos Google</inertia-link></li>
                    <li class="breadcrumb-item active">{{ accion }} acceso</li>
                </ol>              
            </div>
            <div class="card-body">                
                <b-form>
                    <b-form-group id="input-group-1" label="Nombre:" label-for="input-1">
                        <b-form-input
                            id="input-1"
                            v-model="accesoGoogle.nombre"
                            placeholder="Nombre"   
                            :readonly="accion == 'Mostrar'"              
                        ></b-form-input>
                        <span v-if="errors.nombre" class="error">{{ errors.nombre[0] }}</span>
                    </b-form-group> 
                    <b-form-group id="input-group-4" label="Correo:" label-for="input-4">
                        <b-form-input
                            id="input-4"
                            v-model="accesoGoogle.correo"
                            placeholder="Correo"   
                            :readonly="accion == 'Mostrar'"              
                        ></b-form-input>
                        <span v-if="errors.correo" class="error">{{ errors.correo[0] }}</span>
                    </b-form-group>    
                    <b-form-group id="input-group-5" label="Cargo:" label-for="input-5">
                        <b-form-input
                            id="input-5"
                            v-model="accesoGoogle.cargo"
                            placeholder="Cargo"   
                            :readonly="accion == 'Mostrar'"              
                        ></b-form-input>
                        <span v-if="errors.cargo" class="error">{{ errors.cargo[0] }}</span>
                    </b-form-group>                 
                    <b-button v-if="accion == 'Crear'" @click="registrar"  variant="success">Registrar</b-button>
                    <b-button v-else-if="accion == 'Mostrar'" @click="accion = 'Editar'" variant="warning">Editar</b-button>
                    <b-button v-else-if="accion == 'Editar'" @click="actualizar" variant="success">Actualizar</b-button>
                </b-form>                                
            </div>
        </div>            
    </app-layout>
</template>

<script>
    const axios = require('axios') 
    import AppLayout from '@/Layouts/AppLayout'    

    export default {
        name: "accesos-google.mostrar",
        props: ["accesoGoogle"],
        components: {
            AppLayout,                      
        },
        data() {
            return {
                app_url: this.$root.app_url,                      
                accion: '',
                errors: []

            };
        },       
        created() {
            if (!this.accesoGoogle.id) {
                this.accion = 'Crear'
            }
            else {
                this.accion = 'Mostrar'
            }            
        },
        methods: {            
            async registrar() {
                this.errors = []

                try {
                    const response = await axios.post(`${this.app_url}/accesos-google`, this.accesoGoogle)                    
                    
                    if (!response.data.error) {    
                        this.makeToast(response.data.successMessage, 'success')                                                                                                             
                    }
                    else {                        
                        this.makeToast(response.data.errorMessage, 'danger')        
                    }

                    this.accion = 'Mostrar'

                } catch (error) {
                    console.log(error)
                    if (error.response.status==422) {
                        this.errors = error.response.data.errors;                         
                    }
                    else {
                        this.makeToast('Se ha producido un error, vuelve a intentarlo más tarde', 'danger')        
                    }                      
                }      
            },       
            async actualizar() {
                this.errors = []
                
                try {
                    const response = await axios.post(`${this.app_url}/accesos-google/${this.accesoGoogle.id}`, this.accesoGoogle)
                    
                    if (!response.data.error) {    
                        this.makeToast(response.data.successMessage, 'success')                                                                                                             
                    }
                    else {                        
                        this.makeToast(response.data.errorMessage, 'danger')        
                    }

                    this.accion = 'Mostrar'

                } catch (error) {
                    console.log(error)
                    if (error.response.status==422) {
                        this.errors = error.response.data.errors;                         
                    }
                    else {
                        this.makeToast('Se ha producido un error, vuelve a intentarlo más tarde', 'danger')        
                    }                   
                }      
            },       
            makeToast(message, variant = null) {
                this.$bvToast.toast(message, {
                    title: `Accesos Google`,
                    variant: variant,
                    solid: true
                })
            }     
        },
    }
</script>
<style scoped>
    .error {
        color: red;
    }
</style>
