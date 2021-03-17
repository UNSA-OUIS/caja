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
                        <inertia-link :href="route('tipos-concepto.iniciar')"
                            >Lista de tipos de concepto</inertia-link
                        >
                    </li>
                    <li class="breadcrumb-item active">
                        {{ accion }} tipo de concepto
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
                            v-model="tiposConcepto.nombre"
                            placeholder="Nombre de tipo de concepto"
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
const axios = require("axios");
import AppLayout from "@/Layouts/AppLayout";

export default {
    name: "tipos-concepto.mostrar",
    props: ["tiposConcepto"],
    components: {
        AppLayout
    },
    data() {
        return {
            accion: "",
        };
    },
    created() {
        if (!this.tiposConcepto.id) {
            this.accion = "Crear";
        } else {
            this.accion = "Mostrar";
        }
    },
    methods: {
        registrar() {
          this.$inertia.post(
                route("tipos-concepto.registrar"),
                this.tiposConcepto
            );
        },
        actualizar() {
            this.$inertia.post(
                route("tipos-concepto.actualizar", [this.tiposConcepto.id]),
                this.tiposConcepto
            );
        }
    }
};
</script>
