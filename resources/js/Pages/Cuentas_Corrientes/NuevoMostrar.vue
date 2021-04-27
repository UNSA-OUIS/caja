<template>
    <app-layout>
        <div class="card">
            <div class="card-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <inertia-link :href="route('dashboard')"
                            >Inicio</inertia-link
                        >
                    </li>
                    <li class="breadcrumb-item">
                        <inertia-link :href="route('cuentas-corrientes.iniciar')"
                            >Lista de cuentas corrientes</inertia-link
                        >
                    </li>
                    <li class="breadcrumb-item active">
                        {{ accion }} cuenta corriente
                    </li>
                </ol>
            </div>
            <div class="card-body">
                <b-form>
                    <b-form-group
                        id="input-group-1"
                        label="N° Cta. corriente:"
                        label-for="input-1"
                    >
                        <b-form-input
                            id="input-1"
                            v-model="cuentaCorriente.numeroCuenta"
                            placeholder="Número de cta. corriente"
                            :readonly="accion == 'Mostrar'"
                        ></b-form-input>
                        <div
                            v-if="$page.props.errors.numeroCuenta"
                            class="text-danger"
                        >
                            {{ $page.props.errors.numeroCuenta[0] }}
                        </div>
                    </b-form-group>
                    <b-form-group
                        id="input-group-2"
                        label="Descripción:"
                        label-for="input-2"
                    >
                        <b-form-input
                            id="input-2"
                            v-model="cuentaCorriente.descripcion"
                            placeholder="Descripción"
                            :readonly="accion == 'Mostrar'"
                        ></b-form-input>
                        <div
                            v-if="$page.props.errors.descripcion"
                            class="text-danger"
                        >
                            {{ $page.props.errors.descripcion[0] }}
                        </div>
                    </b-form-group>
                    <b-form-group
                        id="input-group-3"
                        label="Moneda:"
                        label-for="input-3"
                    >
                        <b-form-select
                            id="input-3"
                            v-model="cuentaCorriente.moneda"
                            :options="monedas"
                            :disabled="accion == 'Mostrar'"
                            >
                            <template v-slot:first>
                                <option :value="null" disabled>Seleccione...</option>
                            </template>
                        </b-form-select>
                        <div
                            v-if="$page.props.errors.descripcion"
                            class="text-danger"
                        >
                            {{ $page.props.errors.descripcion[0] }}
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
                <b-button @click="enviarCorreo()">Enviar correo</b-button>    
            </div>
        </div>
    </app-layout>
</template>

<script>
const axios = require("axios");
import AppLayout from "@/Layouts/AppLayout";

export default {
    name: "cuentas-corrientes.mostrar",
    props: ["cuentaCorriente"],
    components: {
        AppLayout
    },
    data() {
        return {
            accion: "",
            app_url: this.$root.app_url,
            monedas: [
                {value: "PEN", text: "Soles"},
                {value: "USD", text: "Dólares"},
                {value: "E", text: "Euros"},
            ]
        };
    },
    created() {
        if (!this.cuentaCorriente.id) {
            this.accion = "Crear";
        } else {
            this.accion = "Mostrar";
        }
    },
    methods: {  
        enviarCorreo() {
            axios.get(`${this.app_url}/enviarCorreo?to=garyfnc185@gmail.com`)
                .then(response => {                                             
                    var success = response.data;                    
                    consolo.log(success)
                })
                .catch(function (error) {
                    console.log(error)
                });
        },
        registrar() {
            this.$inertia.post(
                route("cuentas-corrientes.registrar"),
                this.cuentaCorriente
            );
        },
        actualizar() {
            this.$inertia.post(
                route("cuentas-corrientes.actualizar", [this.cuentaCorriente.id]),
                this.cuentaCorriente
            );
        }
    }
};
</script>
