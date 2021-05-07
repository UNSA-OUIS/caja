<template>
    <app-layout>
        <div class="card">
            <div class="card-header">
                <ol class="breadcrumb float-left">
                    <li class="breadcrumb-item">
                        <inertia-link :href="`${app_url}/dashboard`">Inicio</inertia-link>
                    </li>
                    <li class="breadcrumb-item">
                        <inertia-link :href="route('comprobantes.iniciar')">Lista de cobros</inertia-link>
                    </li>
                    <li class="breadcrumb-item active">Buscar usuario</li>
                </ol>                
            </div>
            <div class="card-body">
                <b-tabs pills card vertical>
                    <b-tab title="Boleta" active>                        
                        <b-tabs
                            active-nav-item-class="font-weight-bold text-uppercase text-danger"
                            active-tab-class="font-weight-bold"
                            content-class="mt-3"
                            justified                                
                        >
                            <b-tab title="Alumnos" active>                        
                                <tab-alumnos></tab-alumnos>       
                            </b-tab>
                            <b-tab title="Particulares">
                                <tab-particulares></tab-particulares>                          
                            </b-tab>
                            <b-tab title="Docentes">
                                <tab-docentes></tab-docentes>  
                            </b-tab>
                            <b-tab title="Dependencias">
                                <tab-dependencias></tab-dependencias>  
                            </b-tab>                            
                        </b-tabs>                        
                    </b-tab>
                    <b-tab title="Factura">                                                                   
                        <tab-empresas></tab-empresas>                               
                    </b-tab>           
                    <b-tab title="Nota de débito">                                                                  
                    </b-tab>                    
                    <b-tab title="Nota de crédito">                                                                  
                    </b-tab>                    
                </b-tabs>                
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import TabAlumnos from "./TabAlumnos/Inicio";
import TabDocentes from "./TabDocentes/Inicio";
import TabDependencias from "./TabDependencias/Inicio";
import TabParticulares from "./TabParticulares/Inicio";
import TabEmpresas from "./TabEmpresas/Inicio";

export default {
    name: "comprobantes.busqueda",
    components: {
        AppLayout,
        TabAlumnos,
        TabDocentes,
        TabDependencias,
        TabParticulares,
        TabEmpresas
    },
    data() {
        return {
            app_url: this.$root.app_url,            
        };
    },    
};
</script>
