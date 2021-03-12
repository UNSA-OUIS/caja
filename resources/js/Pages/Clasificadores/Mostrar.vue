<template>
    <app-layout>                    
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Mostrar clasificador</h3>                
            </div>
            <div class="card-body">                
                <b-form @submit.prevent="actualizar">
                    <b-form-group id="input-group-1" label="Nombre:" label-for="input-1">
                        <b-form-input
                            id="input-1"
                            v-model="clasificador.nombre"
                            placeholder="Nombre del clasificador"   
                            :disabled="!editar"              
                        ></b-form-input>
                    </b-form-group>
                    <b-button v-show="!editar" @click="editar=true" variant="warning">Editar</b-button>
                    <b-button type="submit" v-show="editar" variant="success">Actualizar</b-button>
                </b-form>                                
            </div>
        </div>            
    </app-layout>
</template>

<script>
    const axios = require('axios') 
    import AppLayout from '@/Layouts/AppLayout'    

    export default {
        name: "clasificadores.mostrar",
        props: ["clasificador"],
        components: {
            AppLayout,                      
        },
        data() {
            return {
                app_url: this.$root.app_url,      
                editar: false,            
            };
        },        
        methods: {
            async actualizar() {
                try {
                    const response = await axios.post(`${this.app_url}/clasificadores/${this.clasificador.id}`, this.clasificador)
                    
                    if (!response.data.error) {    
                        this.makeToast(response.data.successMessage, 'success')                                                                                                             
                    }
                    else {                        
                        this.makeToast(response.data.errorMessage, 'danger')        
                    }

                    this.editar = false
                } catch (error) {
                    console.log(error)
                    this.makeToast('Se ha producido un error, vuelve a intentarlo más tarde', 'danger')        
                }      
            },       
            makeToast(message, variant = null) {
                this.$bvToast.toast(message, {
                    title: `Clasificadores`,
                    variant: variant,
                    solid: true
                })
            }     
        },
    }
</script>
