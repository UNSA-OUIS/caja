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
                        Lista de comprobantes
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
                        <div class="d-flex justify-content-center mb-3">                                                         
                            <table>
                                <caption class="mb-3" style="caption-side: top;">BÚSQUEDA POR PARTICULAR</caption>
                                <tr>
                                    <th class="text-right">
                                        <label class="mr-sm-2" for="cui">DNI</label>
                                    </th>
                                    <td>
                                        <b-form-input
                                            id="dni"
                                            class="mb-2 mr-sm-2 mb-sm-0"                                            
                                        ></b-form-input>                                    
                                    </td>
                                    <td>
                                        <b-button class="ml-sm-2" variant="primary">Buscar</b-button>
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
                                            class="mb-2 mr-sm-2 mb-sm-0"                                    
                                        ></b-form-input>                                
                                    </td>
                                    <td>
                                        <b-button class="ml-sm-2" variant="primary">Buscar</b-button>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right">
                                        <label class="mr-sm-2" for="ap_materno">Ap. Materno</label>
                                    </th>
                                    <td>
                                        <b-form-input
                                            id="ap_materno"
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
                                            class="mb-2 mr-sm-2 mb-sm-0"                                    
                                        ></b-form-input>                                
                                    </td>
                                    <td></td>
                                </tr>
                            </table>                       
                        </div> 
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

export default {
    name: "comprobantes.busqueda",
    components: {
        AppLayout,
        TabAlumnos,
        TabDocentes,
        TabDependencias
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
