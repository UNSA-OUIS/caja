<template>
    <app-layout>
        <div class="card">
            <div class="card-header">
                <ol class="breadcrumb float-left">
                    <li class="breadcrumb-item">
                        <inertia-link :href="`${app_url}/dashboard`"
                            >Inicio</inertia-link
                        >
                    </li>
                    <li class="breadcrumb-item active">
                        Buscar usuario
                    </li>
                </ol>                
            </div>
            <div class="card-body">
                <b-tabs
                    active-nav-item-class="font-weight-bold text-uppercase text-danger"
                    active-tab-class="font-weight-bold"
                    content-class="mt-3"
                >
                    <b-tab title="Alumnos" active>                        
                        <tab-alumnos></tab-alumnos>       
                    </b-tab>
                    <b-tab title="Docentes">
                        <tab-docentes></tab-docentes>  
                    </b-tab>
                    <b-tab title="Dependencias">
                        <tab-dependencias></tab-dependencias>  
                    </b-tab>
                    <b-tab title="Particulares">
                        <tab-particulares></tab-particulares>                          
                    </b-tab>
                </b-tabs>
            </div>
        </div>
    </app-layout>
</template>

<script>
const axios = require("axios");
import AppLayout from "@/Layouts/AppLayout";
import TabAlumnos from "./TabAlumnos/Inicio";
import TabDocentes from "./TabDocentes/Inicio";
import TabDependencias from "./TabDependencias/Inicio";
import TabParticulares from "./TabParticulares/Inicio";

export default {
    name: "comprobantes.busqueda",
    components: {
        AppLayout,
        TabAlumnos,
        TabDocentes,
        TabDependencias,
        TabParticulares
    },
    data() {
        return {
            app_url: this.$root.app_url,
            cui: '',
            escuelas: []
        };
    },
    methods: {                
        async buscarCuiAlumno() {
            try {
                const response = await axios.get(`${this.app_url}/buscarCuiAlumno/${this.cui}`)
                this.escuelas = response.data               
            } catch (error) {
                console.log(error)    
            }      
        }
    }
};
</script>
