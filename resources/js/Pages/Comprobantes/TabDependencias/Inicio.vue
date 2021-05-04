<template>
    <div>
        <template v-if="!showDependencias">
            <div class="d-flex justify-content-center mb-3">    
                <form @submit.prevent="buscarDependencia" id="dependencia_form"></form>                                                                         
                <table>
                    <caption class="mb-3" style="caption-side: top; text-align:center;">BÚSQUEDA POR DEPENDENCIA</caption>
                    <tr>
                        <th class="text-right">
                            <label class="mr-sm-2" for="nombre">NOMBRE</label>
                        </th>
                        <td>
                            <b-form-input
                                id="nombre"
                                v-model="nomb_depe"
                                class="mb-2 mr-sm-2 mb-sm-0"       
                                form="dependencia_form"                                          
                            ></b-form-input>                                    
                        </td>
                        <td>
                            <b-button form="dependencia_form" type="submit" class="ml-sm-2" variant="info">
                                <b-icon icon="search"></b-icon> Buscar
                            </b-button>
                        </td>
                    </tr>                                                                                                
                </table>                                                                            
            </div>             
        </template>        
        <template v-if="showDependencias">
            <dependencias :dependencias="dependencias"></dependencias>
        </template>
    </div>    
</template>
<script>
const axios = require("axios");
import Dependencias from "./Dependencias";

export default {
    name: "comprobantes.tab-dependencias",    
    components: {                        
        Dependencias
    },
    data() {
        return {
            app_url: this.$root.app_url,
            nomb_depe: '',                        
            dependencias: [],            
            showDependencias: false
        };
    },
    methods: {                
        async buscarDependencia() {            
            try {
                const response = await axios.get(`${this.app_url}/buscarDependencia/${this.nomb_depe}`)
                this.dependencias = response.data                               

                if (this.dependencias.length == 1) {
                    this.mostrarComprobante(this.dependencias[0])                    
                }
                else {
                    this.showDependencias = true                    
                }              
            } catch (error) {
                console.log(error)
            }      
        },        
        mostrarComprobante(dependencia) {       
            this.$inertia.get(route('comprobantes.crear_dependencia'), {                
                'dependencia': dependencia
            })
        },        
        
    }
};
</script>