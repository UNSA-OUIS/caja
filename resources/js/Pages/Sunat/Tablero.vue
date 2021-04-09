<template>
    <app-layout>
        <div class="row">
            <div class="col-md-6 col-xl-2">
                <div class="card">
                    <div class="card-body">
                        <div class="float-right mt-2">
                            <div id="total-revenue-chart"></div>
                        </div>
                        <div>
                            <h4 class="mb-1 mt-1 text-primary">
                                <span data-plugin="counterup">No Enviado</span>
                            </h4>
                            <p class="text-muted mb-0">{{ noEnviado }}
                            <p class="text-muted mb-0" v-if="noEnviado == 1">comprobante</p>
                            <p class="text-muted mb-0" v-else>comprobantes</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end col-->

            <div class="col-md-6 col-xl-2">
                <div class="card">
                    <div class="card-body">
                        <div class="float-right mt-2">
                            <div id="total-revenue-chart"></div>
                        </div>
                        <div>
                            <h4 class="mb-1 mt-1 text-warning">
                                <span data-plugin="counterup">Observado</span>
                            </h4>
                            <p class="text-muted mb-0">{{ observado }}
                            <p class="text-muted mb-0" v-if="observado == 1">comprobante</p>
                            <p class="text-muted mb-0" v-else>comprobantes</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end col-->

            <div class="col-md-6 col-xl-2">
                <div class="card">
                    <div class="card-body">
                        <div class="float-right mt-2">
                            <div id="total-revenue-chart"></div>
                        </div>
                        <div>
                            <h4 class="mb-1 mt-1 text-danger">
                                <span data-plugin="counterup">Rechazado</span>
                            </h4>
                            <p class="text-muted mb-0">{{ rechazado }}
                            <p class="text-muted mb-0" v-if="rechazado == 1">comprobante</p>
                            <p class="text-muted mb-0" v-else>comprobantes</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end col-->

            <div class="col-md-6 col-xl-2">
                <div class="card">
                    <div class="card-body">
                        <div class="float-right mt-2">
                            <div id="total-revenue-chart"></div>
                        </div>
                        <div>
                            <h4 class="mb-1 mt-1 text-secondary">
                                <span data-plugin="counterup">Anulado</span>
                            </h4>
                            <p class="text-muted mb-0">{{ anulado }}
                            <p class="text-muted mb-0" v-if="anulado == 1">comprobante</p>
                            <p class="text-muted mb-0" v-else>comprobantes</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end col-->

            <div class="col-md-6 col-xl-2">
                <div class="card">
                    <div class="card-body">
                        <div class="float-right mt-2">
                            <div id="total-revenue-chart"></div>
                        </div>
                        <div>
                            <h4 class="mb-1 mt-1 text-success">
                                <span data-plugin="counterup">Aceptado</span>
                            </h4>
                            <p class="text-muted mb-0">{{ aceptado }}
                            <p class="text-muted mb-0" v-if="aceptado == 1">comprobante</p>
                            <p class="text-muted mb-0" v-else>comprobantes</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end col-->
        </div>
        <!-- end row-->

        <div>
            <div>
                <b-card-group deck>
                    <b-card
                        border-variant="primary"
                        header="No Enviado"
                        header-bg-variant="primary"
                        header-text-variant="white"
                        align="center"
                    >
                        <b-card-text
                            >{{ noEnviado }}
                            <p v-if="noEnviado == 1">comprobante</p>
                            <p v-else>comprobantes</p></b-card-text
                        >
                    </b-card>
                    <b-card
                        border-variant="warning"
                        header="Observado"
                        header-bg-variant="warning"
                        header-text-variant="white"
                        align="center"
                    >
                        <b-card-text
                            >{{ observado }}
                            <p v-if="observado == 1">comprobante</p>
                            <p v-else>comprobantes</p></b-card-text
                        >
                    </b-card>
                    <b-card
                        border-variant="danger"
                        header="Rechazado"
                        header-bg-variant="danger"
                        header-text-variant="white"
                        align="center"
                    >
                        <b-card-text
                            >{{ rechazado }}
                            <p v-if="rechazado == 1">comprobante</p>
                            <p v-else>comprobantes</p></b-card-text
                        >
                    </b-card>
                    <b-card
                        border-variant="secondary"
                        header="Anulado"
                        header-bg-variant="secondary"
                        header-text-variant="white"
                        align="center"
                    >
                        <b-card-text
                            >{{ anulado }}
                            <p v-if="anulado == 1">comprobante</p>
                            <p v-else>comprobantes</p></b-card-text
                        >
                    </b-card>
                    <b-card
                        border-variant="success"
                        header="Aceptado"
                        header-bg-variant="success"
                        header-text-variant="white"
                        align="center"
                    >
                        <b-card-text
                            >{{ aceptado }}
                            <p v-if="aceptado == 1">comprobante</p>
                            <p v-else>comprobantes</p></b-card-text
                        >
                    </b-card>
                </b-card-group>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
export default {
    name: "sunat.tablero",
    props: ["noEnviado", "observado", "rechazado", "anulado", "aceptado"],
    components: {
        AppLayout
    },
    created(){
        this.$store.dispatch("getEstados");
    }
};
</script>

<style></style>
