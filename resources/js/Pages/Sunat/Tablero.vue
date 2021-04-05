<template>
    <app-layout>
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
    }
};
</script>

<style></style>
