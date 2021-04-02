<template>
    <app-layout>
        <div>
            <div>
                <b-card-group deck>
                    <b-card
                        border-variant="warning"
                        header="No Enviado"
                        header-bg-variant="warning"
                        header-text-variant="white"
                        align="center"
                    >
                        <b-card-text
                            >{{ noenviado }}
                            <p>comprobantes</p></b-card-text
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
                            <p>comprobantes</p></b-card-text
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
                            <p>comprobantes</p></b-card-text
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
                            <p>comprobantes</p></b-card-text
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
    props: ["noenviado", "rechazado", "anulado", "aceptado"],
    components: {
        AppLayout
    }
};
</script>

<style></style>
