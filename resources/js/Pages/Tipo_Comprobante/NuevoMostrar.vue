<template>
    <app-layout>
        <div class="card">
            <div class="card-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <inertia-link :href="`${app_url}/dashboard`"
                            >Inicio</inertia-link
                        >
                    </li>
                    <li class="breadcrumb-item">
                        <inertia-link :href="route('tipo-comprobante.iniciar')"
                            >Lista de tipos de comprobante</inertia-link
                        >
                    </li>
                    <li class="breadcrumb-item active">
                        {{ accion }} tipo de comprobante
                    </li>
                </ol>
            </div>
            <div class="card-body">
                <b-form>
                    <b-form-group
                        id="input-group-1"
                        label="Nombre:"
                        label-for="input-1"
                    >
                        <b-form-input
                            id="input-1"
                            v-model="tipoComprobante.nombre"
                            placeholder="Nombre de tipo de comprobante"
                            :readonly="accion == 'Mostrar'"
                        ></b-form-input>
                        <div
                            v-if="$page.props.errors.nombre"
                            class="text-danger"
                        >
                            {{ $page.props.errors.nombre[0] }}
                        </div>
                    </b-form-group>
                    <b-button
                        v-if="accion == 'Crear'"
                        @click="registrar"
                        variant="success"
                        >Registrar</b-button
                    >
                    <b-button
                        v-else-if="accion == 'Mostrar'"
                        @click="accion = 'Editar'"
                        variant="warning"
                        >Editar</b-button
                    >
                    <b-button
                        v-else-if="accion == 'Editar'"
                        @click="actualizar"
                        variant="success"
                        >Actualizar</b-button
                    >
                </b-form>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";

export default {
    name: "tipo-comprobante.mostrar",
    props: ["tipoComprobante"],
    components: {
        AppLayout
    },
    data() {
        return {
            accion: ""
        };
    },
    created() {
        if (!this.tipoComprobante.id) {
            this.accion = "Crear";
        } else {
            this.accion = "Mostrar";
        }
    },
    methods: {
        async registrar() {
            this.$inertia.post(
                route("tipo-comprobante.registrar"),
                this.tipoComprobante
            );
        },
        actualizar() {
            this.$inertia.post(
                route("tipo-comprobante.actualizar", [this.tipoComprobante.id]),
                this.tipoComprobante
            );
        }
    }
};
</script>
