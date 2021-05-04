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
                    :items="docentes"
                >                       
                    <template v-slot:cell(nombre)="row">
                        <a href="#" @click="buscarCodigoDocente(row.item)">{{ row.item.apn }}</a>
                    </template>
                </b-table>                
            </b-col>                
        </b-row>        
    </b-container>                                                 
</template>

<script>
const axios = require("axios");

export default {
    name: "comprobantes.tab-docentes.docentes",  
    props: ["docentes"],    
    data() {
        return {
            app_url: this.$root.app_url,    
            docente: {},
            fields: [
                { key: "codper", label: "Código", sortable: true, class: "text-center" },
                { key: "nombre", label: "Nombre", sortable: true },                                
            ],                
        };
    },        
    methods: {        
        async buscarCodigoDocente(docente) {
            try {
                const response = await axios.get(`${this.app_url}/buscarCodigoDocente/${docente.codper}`)
                this.docente = response.data               
                this.mostrarComprobante(this.docente)                    
                
            } catch (error) {
                console.log(error)
            }      
        },
        mostrarComprobante(docente) {       
            this.$inertia.get(route('comprobantes.crear_docente'), {                
                'docente': docente
            })
        },  
    }
};
</script>
