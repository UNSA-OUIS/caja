<template>     
    <b-container>        
        <b-row class="justify-content-lg-center">                
            <b-col cols="8">
                <div class="card-body">                  
                    <b-form>
                        <b-row>
                            <b-col cols="6">
                                <b-form-group id="input-group-1" label="RUC:" label-for="input-1">
                                    <b-form-input
                                        class="text-center"
                                        id="input-1"
                                        v-model="empresa.ruc"
                                        type="text"
                                        readonly                                                                   
                                    ></b-form-input>
                                    <span v-if="errors.ruc" class="text-danger">{{ errors.ruc[0] }}</span>
                                </b-form-group>
                            </b-col>
                            <b-col cols="6">
                                <b-form-group id="input-group-2" label="Email:" label-for="input-2">
                                    <b-form-input
                                        id="input-2"
                                        v-model="empresa.email"
                                        type="text"
                                        placeholder="Ingrese correo electrónico"                                                                   
                                    ></b-form-input>
                                    <span v-if="errors.email" class="text-danger">{{ errors.email[0] }}</span>
                                </b-form-group>
                            </b-col>                        
                        </b-row>
                        <b-row>
                            <b-col cols="6">
                                <b-form-group id="input-group-3" label="Razón social:" label-for="input-3">
                                    <b-form-input
                                        id="input-3"
                                        v-model="empresa.razon_social"
                                        type="text"
                                        placeholder="Ingrese razón social"                                                                 
                                    ></b-form-input>
                                    <span v-if="errors.razon_social" class="text-danger">{{ errors.razon_social[0] }}</span>
                                </b-form-group>
                            </b-col>
                            <b-col cols="6">
                                <b-form-group id="input-group-4" label="Dirección:" label-for="input-4">
                                    <b-form-input
                                        id="input-4"
                                        v-model="empresa.direccion"
                                        type="text"
                                        placeholder="Ingrese domicilio legal"                                                                 
                                    ></b-form-input>
                                    <span v-if="errors.direccion" class="text-danger">{{ errors.direccion[0] }}</span>
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
    name: "tabempresas.registro",  
    props: ["empresa"],    
    data() {
        return {
            app_url: this.$root.app_url, 
            errors: []                           
        };
    },        
    methods: {        
        async registrar() {
            try {
                const response = await axios.post(`${this.app_url}/registrarEmpresa`, this.empresa)                
                if (!response.data.error) {
                    this.mostrarComprobante(this.empresa)                    
                }
                else {
                    alert(response.data.errorMessage)
                }               
            } catch (error) {
                if (error.response.status == 422) {                    
                  this.errors = error.response.data.errors;
                } else {
                  this.$bvToast.toast('No se pudo registrar la empresa.', {
                        title: 'Registro empresa',
                        variant: 'danger',
                        toaster: 'b-toaster-bottom-right',
                        solid: true
                    })
                }
            }      
        },
        mostrarComprobante(empresa) {       
            this.$inertia.get(route('comprobantes.crear'), {
                'tipo_usuario' : 'empresa',
                'empresa': empresa
            })
        },  
    }
};
</script>
