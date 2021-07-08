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
                        Buscar administrado
                    </inertia-link>
                </li>
                <li class="breadcrumb-item active">
                    Nuevo cobro
                </li>
            </ol>
        </nav>
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <span v-if="comprobante.id" class="font-weight-bold">Ver cobro</span>
                <span v-else class="font-weight-bold">Nuevo cobro</span>
                <b-button v-if="comprobante.id && comprobante.cancelado == false" class="btn btn-success ml-auto"
                size="sm" title="Pagar factura" @click="pagar(comprobante)">
                    Pagar factura
                </b-button>
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
                    <template v-if="comprobante.tipo_usuario === 'alumno' && data.tipo_comprobante === 'BOLETA'">
                        <cabecera-alumno :comprobante="comprobante" :data="data"></cabecera-alumno>
                    </template>
                    <template v-else-if="comprobante.tipo_usuario === 'docente' && data.tipo_comprobante === 'BOLETA'">
                        <cabecera-docente :comprobante="comprobante" :data="data"></cabecera-docente>
                    </template>
                    <template v-else-if="comprobante.tipo_usuario === 'dependencia' && data.tipo_comprobante === 'BOLETA'">
                        <cabecera-dependencia :comprobante="comprobante" :data="data"></cabecera-dependencia>
                    </template>
                    <template v-else-if="comprobante.tipo_usuario === 'particular' && data.tipo_comprobante === 'BOLETA'">
                        <cabecera-particular :comprobante="comprobante" :data="data"></cabecera-particular>
                    </template>
                    <template v-else-if="comprobante.tipo_usuario === 'empresa' && data.tipo_comprobante === 'FACTURA'">
                        <cabecera-empresa :comprobante="comprobante" :data="data"></cabecera-empresa>
                    </template>
                    <template v-else-if="data.tipo_comprobante == 'NOTA DE DÉBITO' || data.tipo_comprobante == 'NOTA DE CRÉDITO'">
                        <cabecera-nota-mostrar v-if="comprobante.id" :comprobante="comprobante" :data="data"></cabecera-nota-mostrar>
                        <cabecera-nota v-else :comprobante="comprobante" :data="data"></cabecera-nota>
                    </template>
                    <template v-if="comprobante.detalles != null">
                        <detalle-mostrar v-if="comprobante.id" :comprobante="comprobante"></detalle-mostrar>
                        <detalle v-else :comprobante="comprobante" :cuentas="data.cuentas"></detalle>
                    </template>
                </div>
            </div>
        </div>
    </app-layout>
</template>
<script>
const axios = require('axios')
import AppLayout from "@/Layouts/AppLayout";
import CabeceraAlumno from "./CabeceraAlumno";
import CabeceraDocente from "./CabeceraDocente";
import CabeceraDependencia from "./CabeceraDependencia";
import CabeceraParticular from "./CabeceraParticular";
import CabeceraEmpresa from "./CabeceraEmpresa";
import CabeceraNota from "./CabeceraNota";
import CabeceraNotaMostrar from "./CabeceraNotaMostrar";
import Detalle from "./Detalle";
import DetalleMostrar from "./DetalleMostrar";

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
        CabeceraNota,
        CabeceraNotaMostrar,
        Detalle,
        DetalleMostrar
    },
    data() {
        return {
            app_url: this.$root.app_url,
        };
    },
    created(){
        if(this.data.email != ''){
            this.comprobante.email = this.data.email;
        }
    },
    methods: {
        pagar(comprobante){
            this.$bvModal.msgBoxConfirm("¿Esta seguro de querer pagar este cobro?", {
                title: "Pagar cobro",
                okVariant: "success",
                okTitle: "SI",
                cancelTitle: "NO",
                centered: true,
            }).then((value) => {
                if (value) {
                    axios.get(`${this.app_url}/pagarFactura`, {
                        params: {
                            comprobante_id: comprobante.id,
                        },
                    }).then((response) => {
                        var success = response.data.successMessage;
                        if (!response.data.error){
                            this.$bvToast.toast(response.data.successMessage, {
                                title: `Cobro pagado`,
                                variant: "success",
                                solid: true,
                            });
                            location.reload();
                        }
                        else{
                            this.$bvToast.toast(response.data.errorMessage, {
                                title: `Cobro no pagado`,
                                variant: "danger",
                                solid: true,
                            });
                        }
                    }).catch(function (error) {
                        console.log(error);
                    });
                }
            });
        }
    }
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
        font-weight: 400;
    }
    .breadcrumb li a {
        color: blue;
    }
    .breadcrumb {
        margin-bottom: 0;
        background-color: white;
    }
</style>
