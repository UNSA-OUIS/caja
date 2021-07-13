<template>
    <app-layout>
        <div class="card" ref="content">
            <div class="card-header d-flex align-items-center">                
                <span class="font-weight-bold">Recibo de ingresos</span>
                <div v-if="clasificadores.length > 0" class="d-flex ml-auto">
                    <b-form-input class="ml-auto" v-model="recibo.correlativo" id="input-1" type="text" placeholder="Nro de recibo"></b-form-input>
                    <b-button class="btn btn-success float-right" @click="registrar()">Registrar</b-button>
                </div>
            </div> 
            <div class="card-body">
                <b-alert
                show
                dismissible
                variant="success"
                v-if="$page.props.successMessage"
                >{{ $page.props.successMessage }}</b-alert
                >
                <b-alert
                show
                dismissible
                variant="danger"
                v-if="$page.props.errorMessage"
                >{{ $page.props.errorMessage }}</b-alert
                >
                <b-row>
                    <b-col cols="6">
                        <v-select
                        v-model="recibo.cajero_id"
                        @search="buscarCajero"
                        :filterable="false"
                        :options="cajeros"
                        :reduce="(cajero) => cajero.cajero_id"
                        label="vista_cajero"
                        placeholder="Ingrese código o nombre del cajero"
                    >
                        <template #search="{attributes, events}">
                            <input
                                class="vs__search"
                                :required="!cajero"
                                v-bind="attributes"
                                v-on="events"
                                v-model="filtro"
                            />
                        </template>
                        <template slot="no-options">
                            Lo sentimos, no hay resultados de coincidencia.
                        </template>
                    </v-select>
                    </b-col>
                    <b-col cols="6">
                        <b-form-group
                        label="Cuenta corriente: "
                        label-cols-sm="6"
                        label-align-sm="right"
                        label-size="sm"
                        label-for="cuentaCorriente"
                        class="mb-0"
                        >
                            <b-form-select id="cuentaCorriente" v-model="recibo.cuenta_corriente_id" :options="cuentas">
                                <template v-slot:first>
                                    <option :value="null" disabled>Seleccione...</option>
                                </template>
                            </b-form-select>
                        </b-form-group>
                    </b-col>
                </b-row>
                <b-row>
                    <b-col cols="6">
                        <b-form-group
                        label="Fecha inicio: "
                        label-cols-sm="6"
                        label-align-sm="right"
                        label-size="sm"
                        label-for="startDate"
                        class="mb-0"
                        >
                        <b-form-datepicker
                            id="startDate"
                            v-model="recibo.fechaInicio"
                            today-button
                            reset-button
                            close-button
                            placeholder="Elige una fecha"
                            size="sm"
                        ></b-form-datepicker>
                         </b-form-group>
                    </b-col>
                    <b-col cols="6">
                        <b-form-group
                        label="Fecha fin: "
                        label-cols-sm="6"
                        label-align-sm="right"
                        label-size="sm"
                        label-for="endDate"
                        class="mb-0"
                        >
                        <b-form-datepicker
                            id="endDate"
                            v-model="recibo.fechaFin"
                            today-button
                            reset-button
                            close-button
                            placeholder="Elige una fecha"
                            size="sm"
                        ></b-form-datepicker>
                         </b-form-group>
                    </b-col>
                </b-row>
                <b-button class="btn btn-success float-right mt-2 mb-2" @click="filterTable()">Generar reporte</b-button>
                <b-table
                    ref="tbl_clasificadores"
                    show-empty
                    striped
                    hover
                    sticky-header
                    bordered
                    small
                    responsive
                    :items="clasificadores"
                    :fields="fields"
                    empty-text="No hay clasificadores para mostrar"
                    empty-filtered-text="No hay clasificadores que coincidan con su búsqueda.">
                    <template v-slot:cell(descripcion)="row">
                        {{ row.item.descripcion }} ({{ row.item.cantidad }})
                    </template>
                    <template v-if="clasificadores.length" slot="bottom-row" slot-scope="">
                        <b-td class="text-right font-weight-bold">{{totalRegistros}} registros</b-td>
                        <b-td class="text-right font-weight-bold">TOTALES:</b-td>
                        <b-td class="text-right font-weight-bold">{{totalMontos}}</b-td>
                    </template>
                </b-table>
            </div>
        </div>
    </app-layout>
</template>

<script>
const axios = require('axios')
import AppLayout from "@/Layouts/AppLayout";
import VueHtml2pdf from 'vue-html2pdf'
import JsonExcel from "vue-json-excel";

export default {
    name: "reportes.consolidado",
    props: ["recibo", "cuentas"],
    components: {
        AppLayout,
        VueHtml2pdf,
        JsonExcel
    },
    data() {
        return {
            app_url: this.$root.app_url,
            cajero: null,
            cajeros: [],
            filtro: "",
            fields: [
                { key: "clasificador_id", label: "Código" },
                { key: "descripcion", label: "Concepto" },
                { key: "subtotal", label: "Importe" ,class: "text-right" },
            ],
            clasificadores: [],
            totalRegistros: 0,
            totalMontos: 0,

        };
    },
    watch : {
        filtro:function(val) {
            this.filtro = val.trim()
        },
        recibo:function(val) {
            this.filtro = ""
        },
        recibo: {
            handler() {
                this.filtro = ""
            },
            deep: true
        }
    },
    created(){
        var today = new Date()
        today.setHours(today.getHours() - 5)
        var dateString = today.toISOString().split("T")[0]
        this.recibo.fechaInicio = dateString;
        this.recibo.fechaFin = dateString;
    },
    methods: {
        buscarCajero(search, loading) {
            loading(true)
            axios.get(`${this.app_url}/buscarCajero?filtro=${search}`)
                .then(response => {
                    this.cajeros = response.data;
                    loading(false)
                })
                .catch(function (error) {
                    console.log(error)
                });
        },
        refreshTable() {
            this.$refs.tbl_clasificadores.refresh();
        },
        registrar() {                                         
            this.$inertia.post(route('recibos.registrar'), this.recibo)
        },
        async filterTable() {
            try {
                let params = "?fechaInicio=" + this.recibo.fechaInicio + "&fechaFin=" + this.recibo.fechaFin + "&cuenta_corriente_id=" + this.recibo.cuenta_corriente_id + "&cajeroId=" + this.recibo.cajero_id
                const response = await axios.get(`${this.app_url}/recibo-ingreso/filter-reporte/cajeros/${params}`)
                this.clasificadores = response.data.clasificadores
                this.totalRegistros = response.data.totalRegistros
                this.totalMontos = response.data.totalMontos
                this.json_data = this.clasificadores.slice()
                this.json_data.push({
                    codigo: "" + this.totalRegistros + " registros",
                    nombre: "TOTALES:",
                    monto: this.totalMontos
                })
                
            } catch (error) {
                console.log(error)
            }
        },
    },
};
</script>
