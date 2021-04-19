<template>
    <div>
        <template v-if="!showDocentes">
            <div class="d-flex justify-content-center mb-3">    
                <form @submit.prevent="buscarCodigoDocente" id="codigo_form"></form> 
                <form @submit.prevent="buscarApnDocente" id="apn_docente_form"></form>                                                          
                <table>
                    <caption class="mb-3" style="caption-side: top;">BÚSQUEDA POR DOCENTE</caption>                        
                    <tr>
                        <th class="text-right">
                            <label class="mr-sm-2" for="cui">Código</label>
                        </th>
                        <td>
                            <b-form-input
                                id="cui"
                                v-model="codigo"
                                class="mb-2 mr-sm-2 mb-sm-0"   
                                required           
                                form="codigo_form"                              
                            ></b-form-input>                                    
                        </td>
                        <td>
                            <b-button form="codigo_form" type="submit" class="ml-sm-2" variant="primary">Buscar</b-button>
                        </td>
                    </tr> 
                    <tr>
                        <td>&nbsp;</td>
                    </tr> 
                    <tr>
                        <th class="text-right">
                            <label class="mr-sm-2" for="ap_paterno">Ap. Paterno <span class="text-danger">*</span></label>
                        </th>
                        <td>
                            <b-form-input
                                id="ap_paterno"     
                                v-model="ap_paterno"                               
                                class="mb-2 mr-sm-2 mb-sm-0"  
                                required        
                                form="apn_docente_form"                                                              
                            ></b-form-input>                                
                        </td>
                        <td>
                            <b-button form="apn_docente_form" type="submit" class="ml-sm-2" variant="primary">Buscar</b-button>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-right">
                            <label class="mr-sm-2" for="ap_materno">Ap. Materno</label>
                        </th>
                        <td>
                            <b-form-input
                                id="ap_materno"
                                v-model="ap_materno"                               
                                class="mb-2 mr-sm-2 mb-sm-0"                                    
                            ></b-form-input>                                
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <th class="text-right">
                            <label class="mr-sm-2" for="nombres">Nombres</label>
                        </th>
                        <td>
                            <b-form-input
                                id="nombres"
                                v-model="nombres"                               
                                class="mb-2 mr-sm-2 mb-sm-0"                                    
                            ></b-form-input>                                
                        </td>
                        <td></td>
                    </tr>                    
                </table>                                                                            
            </div>             
        </template>        
        <template v-if="showDocentes">
            <docentes :docentes="docentes"></docentes>
        </template>
    </div>    
</template>
<script>
const axios = require("axios");
import Docentes from "./Docentes";

export default {
    name: "comprobantes.tab-docentes",    
    components: {                
        Docentes
    },
    data() {
        return {
            app_url: this.$root.app_url,
            codigo: '',
            ap_paterno: '',
            ap_materno: '',
            nombres: '',
            docente: {},            
            docentes: [],            
            showDocentes: false
        };
    },
    methods: {                
        async buscarCodigoDocente() {
            console.log('codigo docente')
            try {
                const response = await axios.get(`${this.app_url}/buscarCodigoDocente/${this.codigo}`)
                this.docente = response.data
                console.log(this.docente)
                this.mostrarComprobante(this.docente)
                
            } catch (error) {
                console.log(error)
            }      
        },
        async buscarApnDocente() {
            try {
                const response = await axios.get(`${this.app_url}/buscarApnDocente`, { 
                                        params: { 
                                            ap_paterno: this.ap_paterno,
                                            ap_materno: this.ap_materno,
                                            nombres: this.nombres,
                                        }
                                })
                
                this.docentes = response.data
                this.showDocentes = true                
            } catch (error) {
                console.log(error)
            }      
        },
        mostrarComprobante(docente) {       
            this.$inertia.post(route('comprobantes.crear'), {
                'tipo_usuario' : 'docente',
                'docente': docente
            })
        },        
        
    }
};
</script>