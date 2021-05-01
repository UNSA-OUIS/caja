<template>     
    <b-container>        
        <b-row class="justify-content-lg-center">                
            <b-col cols="8">
                <div class="card-body">                  
                    <b-form>
                        <b-row>
                            <b-col cols="6">
                                <b-form-group id="input-group-1" label="DNI:" label-for="input-1">
                                    <b-form-input
                                        class="text-center"
                                        id="input-1"
                                        v-model="particular.dni"
                                        type="text"
                                        readonly                                                                   
                                    ></b-form-input>
                                    <span v-if="errors.dni" class="text-danger">{{ errors.dni[0] }}</span>
                                </b-form-group>
                            </b-col>
                            <b-col cols="6">
                                <b-form-group id="input-group-2" label="Email:" label-for="input-2">
                                    <b-form-input
                                        id="input-2"
                                        v-model="particular.email"
                                        type="text"
                                        placeholder="Ingrese correo electrónico"                                                                   
                                    ></b-form-input>
                                    <span v-if="errors.email" class="text-danger">{{ errors.email[0] }}</span>
                                </b-form-group>
                            </b-col>                        
                        </b-row>
                        <b-row>
                            <b-col cols="6">
                                <b-form-group id="input-group-3" label="Nombres:" label-for="input-3">
                                    <b-form-input
                                        id="input-3"
                                        v-model="particular.nombres"
                                        type="text"
                                        placeholder="Ingrese Nombres"                                                                 
                                    ></b-form-input>
                                    <span v-if="errors.nombres" class="text-danger">{{ errors.nombres[0] }}</span>
                                </b-form-group>
                            </b-col>
                            <b-col cols="6">
                                <b-form-group id="input-group-4" label="Apellidos:" label-for="input-4">
                                    <b-form-input
                                        id="input-4"
                                        v-model="particular.apellidos"
                                        type="text"
                                        placeholder="Ingrese apellidos"                                                                 
                                    ></b-form-input>
                                    <span v-if="errors.apellidos" class="text-danger">{{ errors.apellidos[0] }}</span>
                                </b-form-group>
                            </b-col>                        
                        </b-row>                                 
                        <b-button @click="registrar" variant="success">Registrar</b-button>                        
                    </b-form>                                
                </div>           
            </b-col>                
        </b-row>        
    </b-container>                                                 
</template>

<script>
const axios = require("axios");

export default {
    name: "tabparticulares.registro",  
    props: ["particular"],    
    data() {
        return {
            app_url: this.$root.app_url,   
            errors: []                         
        };
    },        
    methods: {        
        async registrar() {
            try {
                const response = await axios.post(`${this.app_url}/registrarParticular`, this.particular)                
                if (!response.data.error) {
                    this.mostrarComprobante(this.particular)                    
                }
                else {
                    alert(response.data.errorMessage)
                }               
            } catch (error) {                
                if (error.response.status == 422) {                    
                  this.errors = error.response.data.errors;
                } else {
                  this.$bvToast.toast('No se pudo registrar el particular.', {
                        title: 'Registro particular',
                        variant: 'danger',
                        toaster: 'b-toaster-bottom-right',
                        solid: true
                    })
                }
            }      
        },
        mostrarComprobante(particular) {       
            this.$inertia.get(route('comprobantes.crear'), {
                'tipo_usuario' : 'particular',
                'particular': particular
            })
        },  
    }
};
</script>
