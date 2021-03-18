<template>
    <app-layout>
        <h1>ENVIO DE TICKETS CDR</h1>
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
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";

export default {
    name: "sunat.ticketCDR",
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