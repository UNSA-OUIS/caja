<template>     
    <b-container>        
        <b-row class="justify-content-md-center" v-if="!showEscuelas">                
            <b-col cols="12" md="auto">
                <b-table 
                    bordered 
                    hover
                    small
                    head-variant="light"
                    :fields="fields" 
                    :items="alumnos"
                >                     
                    <template v-slot:cell(nombre)="row">
                        <a href="#" @click="buscarCuiAlumno(row.item)">{{ row.item.apn }}</a>
                    </template>
                </b-table>                
            </b-col>                
        </b-row>
        <b-row class="justify-content-md-center" v-else-if="showEscuelas">
            <b-col>
                <escuelas :alumno="alumno"></escuelas>
            </b-col>
        </b-row>
    </b-container>                                                 
</template>

<script>
const axios = require("axios");
import Escuelas from "./Escuelas";

export default {
    name: "comprobantes.tab-alumnos.alumnos",  
    props: ["alumnos"],
    components: {        
        Escuelas,        
    },
    data() {
        return {
            app_url: this.$root.app_url,
            alumno: {},            
            fields: [
                { key: "cui", label: "Código", sortable: true, class: "text-center" },
                { key: "nombre", label: "Nombre", sortable: true },                                
            ],    
            showEscuelas: false,        
        };
    },        
    methods: {        
        async buscarCuiAlumno(alumno) {
            try {
                const response = await axios.get(`${this.app_url}/buscarCuiAlumno/${alumno.cui}`)
                this.alumno = response.data                

                if (this.alumno.matriculas.length == 1) {
                    this.mostrarComprobante(this.alumno.matriculas[0])                    
                }
                else {
                    this.showEscuelas = true                    
                }
            } catch (error) {
                console.log(error)
            }      
        },
        mostrarComprobante(matricula) {       
            this.$inertia.get(route('comprobantes.crear_alumno'), {                
                'alumno' : this.alumno,
                'matricula': matricula
            })
        },   
    }
};
</script>
