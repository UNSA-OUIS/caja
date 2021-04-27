<template>
    <app-layout>
        <div class="card">
            <div class="card-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <inertia-link :href="`${app_url}/dashboard`">Inicio</inertia-link>
                    </li>
                    <li class="breadcrumb-item">
                        <inertia-link :href="route('comprobantes.iniciar')">Buscar usuario</inertia-link>
                    </li>
                </ol>
            </div>
            <div class="card-doby">
                <div class="container p-3">
                    <b-row>
                        <b-col>
                            <!--<b-form-group id="input-group-1" label="Serie:" label-for="input-1">
                                <b-form-input
                                    id="input-1"
                                    v-model="comprobante.serie"
                                    placeholder="Serie"
                                    readonly
                                ></b-form-input>
                            </b-form-group>-->

                                <label class="mr-sm-2" for="input-1">Serie: </label>
                                <b-form-input
                                    id="input-1"
                                    v-model="comprobante.serie"
                                    placeholder="Serie"
                                    readonly
                                ></b-form-input>
                        </b-col>
                        <b-col>
                            <b-form-group id="input-group-2" label="Correlativo:" label-for="input-2">
                                <b-form-input
                                    id="input-2"
                                    v-model="comprobante.correlativo"
                                    placeholder="Correlativo"
                                    readonly
                                ></b-form-input>
                            </b-form-group>
                        </b-col>
                        <b-col>
                            <b-form-group id="input-group-3" label="Fecha:" label-for="input-3">
                                <b-form-input
                                    id="input-3"
                                    type="date"
                                    :value="getFechaActual()"
                                ></b-form-input>
                            </b-form-group>
                        </b-col>
                    </b-row>
                    <b-row>
                        <b-col cols="2" v-if="comprobante.tipo_usuario !== 'dependencia'">
                            <b-form-group id="input-group-4" label="DNI:" label-for="input-4">
                                <b-form-input
                                    id="input-4"
                                    v-model="comprobante.dni"
                                    readonly
                                ></b-form-input>
                            </b-form-group>
                        </b-col>
                        <b-col>
                            <b-form-group id="input-group-5" label="Usuario:" label-for="input-5">
                                <b-input-group class="mb-2">
                                    <b-input-group-prepend is-text>
                                        <b-icon icon="person-fill"></b-icon>
                                    </b-input-group-prepend>
                                    <b-form-input
                                        id="input-5"
                                        readonly
                                        v-model="comprobante.usuario"
                                    ></b-form-input>
                                </b-input-group>
                            </b-form-group>
                        </b-col>
                        <b-col>
                            <b-form-group id="input-group-6" label="Email:" label-for="input-6">
                                <b-input-group prepend="@" class="mb-2 mr-sm-2 mb-sm-0">
                                    <b-form-input
                                        id="input-6"
                                        readonly
                                        v-model="comprobante.email"
                                        placeholder="Email"
                                    ></b-form-input>
                                </b-input-group>
                            </b-form-group>
                        </b-col>
                    </b-row>
                    <b-row v-if="comprobante.tipo_usuario === 'alumno'">
                        <b-col>
                            <b-form-group id="input-group-7" label="CUI:" label-for="input-7">
                                <b-form-input
                                    id="input-7"
                                    v-model="comprobante.codigo"
                                    readonly
                                ></b-form-input>
                            </b-form-group>
                        </b-col>
                        <b-col>
                            <b-form-group id="input-group-8" label="Escuela:" label-for="input-8">
                                <b-form-input
                                    id="input-8"
                                    v-model="comprobante.escuela"
                                    readonly
                                ></b-form-input>
                            </b-form-group>
                        </b-col>
                    </b-row>
                    <b-row v-else-if="comprobante.tipo_usuario === 'docente' || comprobante.tipo_usuario === 'dependencia'">
                        <b-col cols="2">
                            <b-form-group id="input-group-9" label="Código:" label-for="input-9">
                                <b-form-input
                                    id="input-9"
                                    v-model="comprobante.codigo"
                                    placeholder="Código"
                                    :readonly="accion == 'Mostrar'"
                                ></b-form-input>
                            </b-form-group>
                        </b-col>
                    </b-row>
                    <hr>
                    <form @submit.prevent="agregarDetalle">
                        <b-row>
                            <b-col cols="5">
                                <v-select
                                    v-model="concepto"
                                    @search="buscarConcepto"
                                    :options="conceptos"
                                    :reduce="concepto => concepto"
                                    label="descripcion"
                                    placeholder="Ingrese código o descripción del concepto"
                                >
                                    <template #search="{attributes, events}">
                                        <input
                                            class="vs__search"
                                            :required="!concepto"
                                            v-bind="attributes"
                                            v-on="events"
                                        />
                                    </template>
                                    <template slot="no-options">
                                        Lo sentimos, no hay resultados de coincidencia.
                                    </template>
                                </v-select>
                            </b-col>
                            <b-col>
                                 <b-button style="height:34px" type="submit" variant="info" class="mb-2" title="Añadir concepto">
                                    <b-icon icon="plus-circle"></b-icon>
                                </b-button>
                            </b-col>
                        </b-row>
                    </form>
                    <b-row class="mt-3">
                        <b-col>
                            <b-table
                                show-empty
                                striped
                                hover
                                bordered
                                small
                                responsive
                                stacked="md"
                                :items="comprobante.detalles"
                                :fields="fields"
                                empty-text="No hay conceptos para mostrar"
                            >
                                <template v-slot:cell(codigo)="row">
                                    <b-form-input
                                        :value="row.item.codigo"
                                        readonly
                                    ></b-form-input>
                                </template>
                                <template v-slot:cell(concepto)="row">
                                    <b-form-input
                                        :value="row.item.descripcion"
                                        readonly
                                    ></b-form-input>
                                </template>
                                <template v-slot:cell(cantidad)="row">
                                    <div>
                                        <b-form-input
                                            v-model="row.item.cantidad"
                                            type="number"
                                        ></b-form-input>
                                    </div>
                                </template>
                                <template v-slot:cell(precio)="row">
                                    <b-form-input
                                        :value="row.item.precio"
                                        readonly
                                    ></b-form-input>
                                </template>
                                <template v-slot:cell(descuento)="row">
                                    <div>
                                        <b-form-select
                                            :options="tipo_descuento"
                                            v-model="row.item.tipo_descuento"
                                        ></b-form-select>
                                        <b-form-input
                                            id="descuento"
                                            @change="calcularDescuento($event, row.item.concepto_id)"
                                            type="number"
                                            value="0.00"
                                        ></b-form-input>
                                    </div>
                                </template>
                                <template v-slot:cell(subTotal)="row">
                                    <div>
                                        <label>S/.
                                            {{
                                                (row.item.valor_unitario *
                                                    row.item.cantidad -
                                                    row.item.descuento)
                                                    | currency
                                            }}
                                        </label>
                                    </div>
                                </template>
                                <template v-slot:cell(acciones)="row">
                                    <b-button
                                        v-if="!row.item.deleted_at"
                                        variant="danger"
                                        size="sm"
                                        title="Eliminar"
                                        @click="eliminarDetalle(row.index)"
                                    >
                                        <b-icon icon="trash"></b-icon>
                                    </b-button>
                                </template>
                                <template slot="bottom-row" slot-scope="">
                                    <b-td /><b-td /><b-td /><b-td /><b-td>Total</b-td>
                                    <b-td>S/.{{ precioTotal | currency }}</b-td
                                    ><b-td />
                                </template>
                            </b-table>
                        </b-col>
                    </b-row>
                    <div v-show="accion == 'Crear'">
                        <b-button variant="success" @click="registrar()">Registrar</b-button>
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
    name: "comprobantes.detalles",
    props: ["comprobante"],
    components: {
        AppLayout
    },
    data() {
        return {
            app_url: this.$root.app_url,
            concepto: null,
            conceptos: [],
            accion: "",
            tipoDescuento: "",
            cantidadState: null,
            conceptosFields: [
                { key: "codigo", label: "Código", sortable: true, class: "text-center" },
                { key: "descripcion", label: "Descripción", sortable: true },
                { key: "precio", label: "Precio", class: "text-center" },
                { key: "anadir", label: "Añadir", class: "text-center" }
            ],
            index: 1,
            totalRows: 1,
            currentPage: 1,
            perPage: 5,
            pageOptions: [5, 10, 15],
            sortBy: null,
            sortDesc: false,
            sortDirection: "asc",
            filter: null,
            tipo_descuento: [
                { value: "A", text: "%" },
                { value: "B", text: "S/." }
            ],
            fields: [
                { key: "codigo", label: "CÓDIGO", class: "text-center", tdClass: "codigo" },
                { key: "concepto", label: "CONCEPTO", class: "text-center", tdClass: "concepto" },
                { key: "cantidad", label: "CANTIDAD", class: "text-center" },
                { key: "precio", label: "PR. UNIT", class: "text-center" },
                { key: "descuento", label: "DESCUENTO", class: "text-center" },
                { key: "subTotal", label: "SUBTOTAL", class: "text-right" },
                { key: "acciones", label: "", class: "text-center" }
            ]
        };
    },
    computed: {
        precioTotal() {
            this.comprobante.total = this.comprobante.detalles.reduce(
                (acc, item) => acc + (item.cantidad * item.valor_unitario - item.descuento), 0);
            return this.comprobante.total;
        },
        conceptosDisponibles(){
            return this.conceptos.filter(option => option.estado == true);
        },
    },
    filters: {
        currency(value) {
            return value.toFixed(2);
        }
    },
    created() {
        if (!this.comprobante.id) {
            this.accion = "Crear";
        } else {
            this.accion = "Mostrar";
        }
    },
    methods: {
        getFechaActual() {
            let fecha_actual = new Date();
            let anio = fecha_actual.getFullYear()
            let mes = fecha_actual.getMonth() + 1
            let dia = fecha_actual.getDate()
            return anio + '-' + mes.toString().padStart(2, "0") + '-' + dia
        },
        buscarConcepto(search, loading) {
            loading(true)
            axios.get(`${this.app_url}/buscarConcepto?filtro=${search}`)
                .then(response => {
                    this.conceptos = response.data;
                    loading(false)
                })
                .catch(function (error) {
                    console.log(error)
                });
        },
        agregarDetalle() {
            this.comprobante.detalles.push(this.concepto)
            this.concepto = null
        },
        eliminarDetalle(index) {
            this.comprobante.detalles.splice(index, 1);
        },
        registrar() {
            this.$bvModal.msgBoxConfirm("¿Esta seguro de querer registrar este comprobante?",
                    {
                        title: "Enviar Comprobante",
                        okVariant: "success",
                        okTitle: "SI",
                        cancelVariant: "danger",
                        cancelTitle: "NO",
                        centered: true
                    }
                )
                .then(async value => {
                    if (value) {
                        this.$inertia.post(route("comprobantes.registrar", this.comprobante));
                    }
                });
        },
        completeConcepto(event, id) {
            let index = this.comprobante.detalles.findIndex(
                detalle => detalle.concepto_id == id
            );
            let conc = this.conceptos.find(option => option.id == event);
            let concIndex = this.conceptos.findIndex(option => option.id == event);
            this.conceptos[concIndex].estado = false;
            this.comprobante.detalles[index].concepto_id = conc.id;
            this.comprobante.detalles[index].codigo = conc.value;
            this.comprobante.detalles[index].valor_unitario = conc.precio;
            this.comprobante.detalles[index].descuento = "0.00";
        },
        calcularDescuento(event, id) {
            let index = this.comprobante.detalles.findIndex(
                detalle => detalle.concepto_id == id
            );
            let conc = this.comprobante.detalles[index].tipo_descuento;
            if (conc == "A") {
                this.comprobante.detalles[index].descuento =
                    (this.comprobante.detalles[index].valor_unitario *
                        this.comprobante.detalles[index].cantidad *
                        event) /
                    100;
            }
            else if (conc == "B") {
                this.comprobante.detalles[index].descuento = event;
            }
            else {
                this.comprobante.detalles[index].descuento = 0;
            }
        },
    },
};
</script>
<style>
    .codigo {
        width: 150px;
    }
    .concepto {
        width: 400px;
    }
</style>
