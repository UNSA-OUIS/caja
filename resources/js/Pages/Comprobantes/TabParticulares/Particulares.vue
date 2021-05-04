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
                    :items="particulares"
                >                       
                    <template v-slot:cell(apn)="row">
                        <a href="#" @click="buscarDniParticular(row.item)">{{ row.item.apellidos }}, {{ row.item.nombres }}</a>
                    </template>
                </b-table>                
            </b-col>                
        </b-row>        
    </b-container>                                                 
</template>

<script>
const axios = require("axios");

export default {
    name: "comprobantes.tab-particulares.particulares",  
    props: ["particulares"],    
    data() {
        return {
            app_url: this.$root.app_url,    
            particular: {},
            fields: [
                { key: "dni", label: "DNI", sortable: true, class: "text-center" },
                { key: "apn", label: "Apellidos y Nombres", sortable: true },                                
            ],                
        };
    },        
    methods: {        
        async buscarDniParticular(particular) {
            try {
                const response = await axios.get(`${this.app_url}/buscarDniParticular/${particular.dni}`)
                this.particular = response.data               
                this.mostrarComprobante(this.particular)                                  
            } catch (error) {
                console.log(error)
            }      
        },
        mostrarComprobante(particular) {       
            this.$inertia.get(route('comprobantes.crear_particular'), {                
                'particular' : particular,                
            })
        },  
    }
};
</script>
