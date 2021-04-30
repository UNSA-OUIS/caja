<template>
    <div>
        <template v-if="!showRegistro && !showEmpresas">
            <div class="d-flex justify-content-center mb-3">    
                <form @submit.prevent="buscarRucEmpresa" id="ruc_form_empresa"></form> 
                <form @submit.prevent="buscarRazonSocialEmpresa" id="razon_social_form_empresa"></form>                                                          
                <table>
                    <caption class="mb-3" style="caption-side: top; text-align:center;">BÚSQUEDA POR EMPRESAS</caption>                        
                    <tr>
                        <th class="text-right">
                            <label class="mr-sm-2" for="ruc_empresa">RUC</label>
                        </th>
                        <td>
                            <b-form-input
                                id="ruc_empresa"
                                v-model="ruc"
                                class="mb-2 mr-sm-2 mb-sm-0"   
                                required           
                                form="ruc_form_empresa"                              
                            ></b-form-input>                                    
                        </td>
                        <td>
                            <b-button form="ruc_form_empresa" type="submit" class="ml-sm-2" variant="info">
                                <b-icon icon="search"></b-icon> Buscar
                            </b-button>
                        </td>
                    </tr> 
                    <tr>
                        <td>&nbsp;</td>
                    </tr> 
                    <tr>
                        <th class="text-right">
                            <label class="mr-sm-2" for="razon_social_empresa">Razon social</label>
                        </th>
                        <td>
                            <b-form-input
                                id="razon_social_empresa"     
                                v-model="razon_social"                               
                                class="mb-2 mr-sm-2 mb-sm-0"  
                                required        
                                form="apn_form_particular"                                                              
                            ></b-form-input>                                
                        </td>
                        <td>
                            <b-button form="razon_social_form_empresa" type="submit" class="ml-sm-2" variant="info">
                                <b-icon icon="search"></b-icon> Buscar
                            </b-button>
                        </td>
                    </tr>                    
                </table>                                                                            
            </div>             
        </template>
        <template v-else-if="showRegistro">
            <registro :empresa="empresa"></registro>
        </template>
        <template v-else-if="showEmpresas">
            <empresas :empresas="empresas"></empresas>
        </template>
    </div>    
</template>
<script>
const axios = require("axios");
import Registro from "./Registro";
import Empresas from "./Empresas";

export default {
    name: "comprobantes.tab-empresas",    
    components: {        
        Registro,
        Empresas
    },
    data() {
        return {
            app_url: this.$root.app_url,
            ruc: '',
            razon_social: '',            
            empresa: {},
            empresas: [],
            showRegistro: false,
            showEmpresas: false
        };
    },
    methods: {                
        async buscarRucEmpresa() {
            try {
                const response = await axios.get(`${this.app_url}/buscarRucEmpresa/${this.ruc}`)
                this.empresa = response.data          
                   
                if (this.empresa) {
                    this.mostrarComprobante(this.empresa)                    
                }
                else {
                    this.empresa = {}                    
                    this.empresa.ruc = this.ruc
                    this.showRegistro = true
                    this.showEmpresas = false
                }
            } catch (error) {
                console.log(error)
            }      
        },
        async buscarRazonSocialEmpresa() {
            try {
                const response = await axios.get(`${this.app_url}/buscarRazonSocialEmpresa`, { 
                                        params: { 
                                            razon_social: this.razon_social,                                            
                                        }
                                })
                
                this.empresas= response.data
                this.showEmpresas = true
                this.showRegistro = false
            } catch (error) {
                console.log(error)
            }      
        },
        mostrarComprobante(empresa) {       
            this.$inertia.get(route('comprobantes.crear'), {
                'tipo_usuario' : 'empresa',
                'empresa' : empresa,                
            })
        },                
    }
};
</script>