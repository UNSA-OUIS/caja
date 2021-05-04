<template>
    <app-layout>
        <div class="card">
            <div class="card-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <inertia-link :href="`${app_url}/dashboard`">Inicio</inertia-link>
                    </li>
                    <li class="breadcrumb-item">
                        <inertia-link :href="route('comprobantes.iniciar')">Lista de comprobantes</inertia-link>
                    </li>
                    <li class="breadcrumb-item active">Nuevo comprobante</li>
                </ol>
            </div>
            <div class="card-doby">
                <div class="container p-3">
                    <b-row>
                        <b-col>
                            <b-form-group id="input-group-0" label="Tipo comprobante:" label-for="input-0">
                                <b-form-input                                    
                                    id="input-0"
                                    v-model="data.tipo_comprobante"                                    
                                    readonly
                                    class="text-center"
                                ></b-form-input>
                            </b-form-group>
                        </b-col>
                        <b-col>
                            <b-form-group id="input-group-1" label="Serie:" label-for="input-1">
                                <b-form-input
                                    id="input-1"
                                    v-model="comprobante.serie"                                    
                                    readonly
                                    class="text-center"
                                ></b-form-input>
                            </b-form-group>
                        </b-col>
                        <b-col>
                            <b-form-group id="input-group-2" label="Correlativo:" label-for="input-2">
                                <b-form-input
                                    id="input-2"
                                    v-model="comprobante.correlativo"                                    
                                    readonly
                                    class="text-center"
                                ></b-form-input>
                            </b-form-group>
                        </b-col>
                        <b-col>
                            <b-form-group id="input-group-3" label="Fecha:" label-for="input-3">
                                <b-form-input
                                    id="input-3"
                                    type="date"
                                    v-model="data.fecha_actual"                                    
                                    readonly
                                    class="text-center"
                                ></b-form-input>
                            </b-form-group>
                        </b-col>
                    </b-row>
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