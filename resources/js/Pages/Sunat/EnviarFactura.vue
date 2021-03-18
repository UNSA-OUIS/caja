<template>
    <app-layout>
        <h1>ENVIO DE FACTURAS ELECTRONICAS</h1>
        <div class="card">
            <div class="card-body">
                <b-alert
                    show
                    variant="success"
                    v-if="$page.props.successMessage"
                    >{{ $page.props.successMessage }}</b-alert
                >
                <b-alert
                    show
                    variant="danger"
                    v-if="$page.props.errorMessage"
                    >{{ $page.props.errorMessage }}</b-alert
                >
                <div>
                    <b-card-group deck>
                        <b-card no-body header="Global">
                            <b-list-group>
                                <b-list-group-item href="#"
                                    >Numero => F00-1</b-list-group-item
                                >
                                <b-list-group-item href="#"
                                    >Fecha de Emision =>
                                    21/07/2020</b-list-group-item
                                >
                                <b-list-group-item href="#"
                                    >Hora de Emision => 13:05</b-list-group-item
                                >
                                <b-list-group-item href="#"
                                    >Forma de Pago => Contado</b-list-group-item
                                >
                                <b-list-group-item href="#"
                                    >Moneda => Sol(PEN)</b-list-group-item
                                >
                                <b-list-group-item href="#"
                                    >RUC de Emisor =>
                                    20123456789</b-list-group-item
                                >
                                <b-list-group-item href="#"
                                    >RUC de Receptor =>
                                    20000000001</b-list-group-item
                                >
                                <b-list-group-item href="#"
                                    >Operaciones Gravadas =>
                                    S/100.00</b-list-group-item
                                >
                                <b-list-group-item href="#"
                                    >Valor Venta => S/100.00</b-list-group-item
                                >
                                <b-list-group-item href="#"
                                    >IGV => S/18.00</b-list-group-item
                                >
                                <b-list-group-item href="#"
                                    >Total Impuestos =>
                                    S/18.00</b-list-group-item
                                >
                                <b-list-group-item href="#"
                                    >Importe Total =>
                                    S/118.00</b-list-group-item
                                >
                            </b-list-group>
                        </b-card>

                        <b-card no-body header="Detalle">
                            <b-list-group flush>
                                <b-list-group-item href="#"
                                    >Codigo => P001</b-list-group-item
                                >
                                <b-list-group-item href="#"
                                    >Descripcion => Tijeras</b-list-group-item
                                >
                                <b-list-group-item href="#"
                                    >Unidad de Medida => Unidad
                                    (NIU)</b-list-group-item
                                >
                                <b-list-group-item href="#"
                                    >Cantidad => 2</b-list-group-item
                                >
                                <b-list-group-item href="#"
                                    >Valor Unitario =>
                                    S/50.00</b-list-group-item
                                >
                                <b-list-group-item href="#"
                                    >Valor Venta => S/100.00</b-list-group-item
                                >
                                <b-list-group-item href="#"
                                    >Tipo de afectacion IGV => Gravado
                                    (10)</b-list-group-item
                                >
                                <b-list-group-item href="#"
                                    >IGV => S/18.00</b-list-group-item
                                >
                                <b-list-group-item href="#"
                                    >Total Impuestos =>
                                    S/18.00</b-list-group-item
                                >
                                <b-list-group-item href="#"
                                    >Precio Unitario =>
                                    S/59.00</b-list-group-item
                                >
                            </b-list-group>
                        </b-card>
                    </b-card-group>
                    <div>
                        <b-overlay
                            :show="busy"
                            rounded
                            opacity="0.6"
                            spinner-small
                            spinner-variant="primary"
                            class="d-inline-block"
                            @hidden="onHidden"
                        >
                            <b-button
                                ref="button"
                                :disabled="busy"
                                @click="onClick"
                                variant="success"
                            >
                                Enviar
                            </b-button>
                        </b-overlay>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
const axios = require("axios");
import AppLayout from "@/Layouts/AppLayout";

export default {
    name: "sunat.enviarFactura",
    components: {
        AppLayout
    },
    data() {
        return {
            app_url: this.$root.app_url
        };
    },
    methods: {
        async registrar() {
            this.$inertia.post(route("sunat.enviarFacturas"));
        }
    }
};
</script>
<style scoped>
.error {
    color: red;
}
</style>
