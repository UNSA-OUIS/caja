<template>
    <app-layout>       
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item ml-auto">
                    <inertia-link :href="route('dashboard')">Inicio</inertia-link>
                </li>
                <li class="breadcrumb-item">
                    <inertia-link :href="route('cobros.iniciar')">
                        Lista de cobros
                    </inertia-link>
                </li>
                <li class="breadcrumb-item">
                    <inertia-link :href="route('cobros.buscarUsuario')">
                        Buscar usuario
                    </inertia-link>
                </li>
                <li class="breadcrumb-item active">
                    Nuevo cobro
                </li>
            </ol>
        </nav>                  
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <span class="font-weight-bold">Nuevo cobro</span>
            </div>            
            <div class="card-doby">
                <div class="container p-3">
                    <div class="form-row">
                      <div class="form-group col-md-3 border border-light">
                        <label class="text-info">Tipo de comprobante:</label>
                        <label class="lbl-data" v-text="data.tipo_comprobante"></label>
                      </div>                      
                      <div class="form-group col-md-3 border border-light">
                        <label class="text-info">Serie:</label>
                        <label class="lbl-data" v-text="comprobante.serie"></label>
                      </div>                      
                      <div class="form-group col-md-3 border border-light">
                        <label class="text-info">Correlativo:</label>
                        <label class="lbl-data" v-text="comprobante.correlativo"></label>
                      </div>       
                      <div class="form-group col-md-3 border border-light">
                        <label class="text-info">Fecha:</label>
                        <label class="lbl-data" v-text="data.fecha_actual"></label>
                      </div>                      
                    </div>                                        
                    <template v-if="comprobante.tipo_usuario === 'alumno'">
                        <cabecera-alumno :comprobante="comprobante" :data="data"></cabecera-alumno>
                    </template>   
                    <template v-else-if="comprobante.tipo_usuario === 'docente'">
                        <cabecera-docente :comprobante="comprobante" :data="data"></cabecera-docente>
                    </template> 
                    <template v-else-if="comprobante.tipo_usuario === 'dependencia'">
                        <cabecera-dependencia :comprobante="comprobante" :data="data"></cabecera-dependencia>
                    </template>   
                    <template v-else-if="comprobante.tipo_usuario === 'particular'">
                        <cabecera-particular :comprobante="comprobante" :data="data"></cabecera-particular>
                    </template>  
                    <template v-else-if="comprobante.tipo_usuario === 'empresa'">
                        <cabecera-empresa :comprobante="comprobante" :data="data"></cabecera-empresa>
                    </template>   
                    <detalle :comprobante="comprobante"></detalle>
                </div>
            </div>
        </div>
    </app-layout>
</template>
<script>
import AppLayout from "@/Layouts/AppLayout";
import CabeceraAlumno from "./CabeceraAlumno";
import CabeceraDocente from "./CabeceraDocente";
import CabeceraDependencia from "./CabeceraDependencia";
import CabeceraParticular from "./CabeceraParticular";
import CabeceraEmpresa from "./CabeceraEmpresa";
import Detalle from "./Detalle";

export default {
    name: "comprobantes.cabecera",
    props: ["comprobante", "data"],
    components: {
        AppLayout,
        CabeceraAlumno,
        CabeceraDocente,
        CabeceraDependencia,
        CabeceraParticular,
        CabeceraEmpresa,
        Detalle        
    },    
    data() {
        return {
            app_url: this.$root.app_url,            
        };
    }, 
};
</script>
<style scoped>
    .lbl-data {
        text-align: center;
        border: 0;
        padding: 0;        
        display: block;
        width: 100%;
        font-size: 1rem;
        margin-bottom: 0;
    }
    .breadcrumb li a {
        color: blue;
    }
    .breadcrumb {
        margin-bottom: 0;
        background-color: white;
    }
</style>
