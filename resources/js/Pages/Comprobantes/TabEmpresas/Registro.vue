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
                                        id="input-1"
                                        v-model="empresa.ruc"
                                        type="text"
                                        readonly                                                                   
                                    ></b-form-input>
                                    <div v-if="$page.props.errors.ruc" class="text-danger">
                                        {{ $page.props.errors.ruc[0] }}
                                    </div>   
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
                                    <div v-if="$page.props.errors.email" class="text-danger">
                                        {{ $page.props.errors.email[0] }}
                                    </div>   
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
                                    <div v-if="$page.props.errors.razon_social" class="text-danger">
                                        {{ $page.props.errors.razon_social[0] }}
                                    </div>   
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
                                    <div v-if="$page.props.errors.direccion" class="text-danger">
                                        {{ $page.props.errors.direccion[0] }}
                                    </div>   
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
                console.log(error)
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
