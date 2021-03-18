<template>
    <app-layout>
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
            </div>
            <div>
                <b-card no-body>
                    <b-tabs pills card>
                        <b-tab title="ENVIO DE COMUNICACION DE BAJA BOLETAS" active
                            ><b-card-text>Tab contents 1</b-card-text></b-tab
                        >
                        <b-tab title="ENVIO DE COMUNICACION DE BAJA FACTURAS"
                            ><b-card-text>Tab contents 2</b-card-text></b-tab
                        >
                    </b-tabs>
                </b-card>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";

export default {
    name: "sunat.resumenBoletas",
    components: {
        AppLayout
    },
    data() {
        return {
            app_url: this.$root.app_url
        };
    },
    methods: {}
};
</script>
<style scoped>
.error {
    color: red;
}
</style>
