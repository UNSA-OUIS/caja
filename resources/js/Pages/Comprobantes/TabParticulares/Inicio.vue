<template>
    <div>
        <template v-if="!showRegistro && !showParticulares">
            <div class="d-flex justify-content-center mb-3">    
                <form @submit.prevent="buscarDniParticular" id="dni_form_particular"></form> 
                <form @submit.prevent="buscarApnParticular" id="apn_form_particular"></form>                                                          
                <table>
                    <caption class="mb-3" style="caption-side: top;">BÚSQUEDA POR PARTICULAR</caption>                        
                    <tr>
                        <th class="text-right">
                            <label class="mr-sm-2" for="dni_particular">DNI</label>
                        </th>
                        <td>
                            <b-form-input
                                id="dni_particular"
                                v-model="dni"
                                class="mb-2 mr-sm-2 mb-sm-0"   
                                required           
                                form="dni_form_particular"                              
                            ></b-form-input>                                    
                        </td>
                        <td>
                            <b-button form="dni_form_particular" type="submit" class="ml-sm-2" variant="primary">Buscar</b-button>
                        </td>
                    </tr> 
                    <tr>
                        <td>&nbsp;</td>
                    </tr> 
                    <tr>
                        <th class="text-right">
                            <label class="mr-sm-2" for="ap_paterno_particular">Ap. Paterno <span class="text-danger">*</span></label>
                        </th>
                        <td>
                            <b-form-input
                                id="ap_paterno_particular"     
                                v-model="ap_paterno"                               
                                class="mb-2 mr-sm-2 mb-sm-0"  
                                required        
                                form="apn_form_particular"                                                              
                            ></b-form-input>                                
                        </td>
                        <td>
                            <b-button form="apn_form_particular" type="submit" class="ml-sm-2" variant="primary">Buscar</b-button>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-right">
                            <label class="mr-sm-2" for="ap_materno_particular">Ap. Materno</label>
                        </th>
                        <td>
                            <b-form-input
                                id="ap_materno_particular"
                                v-model="ap_materno"                               
                                class="mb-2 mr-sm-2 mb-sm-0"                                    
                            ></b-form-input>                                
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <th class="text-right">
                            <label class="mr-sm-2" for="nombres_particular">Nombres</label>
                        </th>
                        <td>
                            <b-form-input
                                id="nombres_particular"
                                v-model="nombres"                               
                                class="mb-2 mr-sm-2 mb-sm-0"                                    
                            ></b-form-input>                                
                        </td>
                        <td></td>
                    </tr>                    
                </table>                                                                            
            </div>             
        </template>
        <template v-else-if="showRegistro">
            <registro :particular="particular"></registro>
        </template>
        <template v-else-if="showParticulares">
            <particulares :particulares="particulares"></particulares>
        </template>
    </div>    
</template>
<script>
const axios = require("axios");
import Registro from "./Registro";
import Particulares from "./Particulares";

export default {
    name: "comprobantes.tab-particulares",    
    components: {        
        Registro,
        Particulares
    },
    data() {
        return {
            app_url: this.$root.app_url,
            dni: '',
            ap_paterno: '',
            ap_materno: '',
            nombres: '',
            particular: {},
            particulares: [],
            showRegistro: false,
            showParticulares: false
        };
    },
    methods: {                
        async buscarDniParticular() {
            try {
                const response = await axios.get(`${this.app_url}/buscarDniParticular/${this.dni}`)
                this.particular = response.data          
                   
                if (this.particular) {
                    this.mostrarComprobante(this.particular)                    
                }
                else {
                    this.particular = {}                    
                    this.particular.dni = this.dni
                    this.showRegistro = true
                    this.showParticulares = false
                }
            } catch (error) {
                console.log(error)
            }      
        },
        async buscarApnParticular() {
            try {
                const response = await axios.get(`${this.app_url}/buscarApnParticular`, { 
                                        params: { 
                                            ap_paterno: this.ap_paterno,
                                            ap_materno: this.ap_materno,
                                            nombres: this.nombres,
                                        }
                                })
                
                this.particulares= response.data
                this.showParticulares = true
                this.showRegistro = false
            } catch (error) {
                console.log(error)
            }      
        },
        mostrarComprobante(particular) {       
            this.$inertia.post(route('comprobantes.crear'), {
                'tipo_usuario' : 'particular',
                'particular' : particular,                
            })
        },                
    }
};
</script>