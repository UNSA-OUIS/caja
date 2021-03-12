<template>
    <app-layout>                    
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Crear clasificador</h3>                
            </div>
            <div class="card-body">                
                <b-form @submit.prevent="registrar">
                    <b-form-group id="input-group-1" label="Nombre:" label-for="input-1">
                        <b-form-input
                            id="input-1"
                            v-model="clasificador.nombre"
                            placeholder="Nombre del clasificador"                                         
                        ></b-form-input>
                    </b-form-group>                    
                    <b-button type="submit" variant="success">Registrar</b-button>
                </b-form>                                
            </div>
        </div>            
    </app-layout>
</template>

<script>
    const axios = require('axios') 
    import AppLayout from '@/Layouts/AppLayout'    

    export default {
        name: "clasificadores.crear",        
        components: {
            AppLayout,                      
        },
        data() {
            return {
                app_url: this.$root.app_url,      
                clasificador: {
                    nombre: ''
                }                
            };
        },        
        methods: {
            async registrar() {
                try {
                    const response = await axios.post(`${this.app_url}/clasificadores`, this.clasificador)
                    
                    if (!response.data.error) {    
                        this.makeToast(response.data.successMessage, 'success')  
                        location.href = `${this.app_url}/clasificadores`;
                    }
                    else {                        
                        this.makeToast(response.data.errorMessage, 'danger')        
                    }
                    
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
