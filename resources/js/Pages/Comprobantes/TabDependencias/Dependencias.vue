<template>     
    <b-container>        
        <b-row class="justify-content-md-center">                
            <b-col cols="12" md="auto">
                <b-table 
                    bordered 
                    hover
                    small
                    head-variant="light"
                    :fields="fields" 
                    :items="dependencias"
                >                       
                    <template v-slot:cell(nomb_depe)="row">
                        <a href="#" @click="buscarCodigoDependencia(row.item)">{{ row.item.nomb_depe }}</a>
                    </template>
                </b-table>                
            </b-col>                
        </b-row>        
    </b-container>                                                 
</template>

<script>
const axios = require("axios");

export default {
    name: "comprobantes.tab-docentes.dependencias",  
    props: ["dependencias"],    
    data() {
        return {
            app_url: this.$root.app_url,    
            dependencia: {},
            fields: [
                { key: "codi_depe", label: "Código", sortable: true, class: "text-center" },
                { key: "nomb_depe", label: "Nombre", sortable: true },                                
            ],                
        };
    },        
    methods: {        
        async buscarCodigoDependencia(dependencia) {
            try {
                const response = await axios.get(`${this.app_url}/buscarCodigoDependencia/${dependencia.codi_depe}`)
                this.dependencia = response.data               
                this.mostrarComprobante(this.dependencia)                    
                
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
