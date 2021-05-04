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
                    :items="empresas"
                >                       
                    <template v-slot:cell(razon_social)="row">
                        <a href="#" @click="buscarRucEmpresa(row.item)">{{ row.item.razon_social }}</a>
                    </template>
                </b-table>                
            </b-col>                
        </b-row>        
    </b-container>                                                 
</template>

<script>
const axios = require("axios");

export default {
    name: "comprobantes.tab-empresas.empresas",  
    props: ["empresas"],    
    data() {
        return {
            app_url: this.$root.app_url,    
            particular: {},
            fields: [
                { key: "ruc", label: "RUC", sortable: true, class: "text-center" },
                { key: "razon_social", label: "Razon social", sortable: true },                                
            ],                
        };
    },        
    methods: {        
        async buscarRucEmpresa(empresa) {
            try {
                const response = await axios.get(`${this.app_url}/buscarRucEmpresa/${empresa.ruc}`)
                this.empresa = response.data               
                this.mostrarComprobante(this.empresa)                                  
            } catch (error) {
                console.log(error)
            }      
        },
        mostrarComprobante(empresa) {       
            this.$inertia.get(route('comprobantes.crear_empresa'), {                
                'empresa' : empresa,                
            })
        },  
    }
};
</script>
