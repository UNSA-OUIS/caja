<template>
    <app-layout>                    
        <div class="card">
            <div class="card-header">                
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><inertia-link :href="`${app_url}/dashboard`">Inicio</inertia-link></li>                    
                    <li class="breadcrumb-item"><inertia-link :href="route('unidades-medida.iniciar')">Lista de unidades de medida</inertia-link></li>
                    <li class="breadcrumb-item active">{{ accion }} unidad de medida</li>
                </ol>              
            </div>
            <div class="card-body">                
                <b-form>
                    <b-form-group id="input-group-1" label="Nombre:" label-for="input-1">
                        <b-form-input
                            id="input-1"
                            v-model="unidadMedida.nombre"
                            placeholder="Nombre de unidad de medida"   
                            :readonly="accion == 'Mostrar'"              
                        ></b-form-input>
                        <span v-if="errors.nombre" class="error">{{ errors.nombre[0] }}</span>
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
        name: "unidades-medida.mostrar",
        props: ["unidadMedida"],
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
            if (!this.unidadMedida.id) {
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
                    const response = await axios.post(`${this.app_url}/unidades-medida`, this.unidadMedida)                    
                    
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
                    const response = await axios.post(`${this.app_url}/unidades-medida/${this.unidadMedida.id}`, this.unidadMedida)
                    
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
                    title: `Unidades de medida`,
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
